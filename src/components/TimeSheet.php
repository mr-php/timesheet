<?php

namespace app\components;

use AJT\Toggl\TogglClient;
use Yii;
use yii\base\Component;
use yii\helpers\Inflector;
use yii\helpers\Json;

/**
 * Class TimeSheet
 * @package app\components
 */
class TimeSheet extends Component
{

    /**
     * @var array
     */
    public $staff = [];

    /**
     * @var array
     */
    public $projects = [];

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $settings = ['staff', 'projects'];
        foreach ($settings as $key) {
            $value = Yii::$app->settings->get('TimeSheetSettingsForm', $key);
            if ($value) {
                $this->$key = Json::decode($value);
            }
        }
    }

    /**
     * @param $togglData
     * @return array
     */
    public function getTimes($togglData)
    {
        $times = $this->convertTimeEntries($togglData);
        $times = $this->applyBaseRates($times);
        $times = $this->applyCapRates($times);
        return $times;
    }

    /**
     * @param array $times
     * @return array
     */
    public function getStaffTimes($times)
    {
        $staffTimes = [];
        foreach ($times as $pid => $staff) {
            foreach ($staff as $sid => $dates) {
                $multiplier = $this->getStaffMultiplier($sid, $pid);
                foreach ($dates as $date => $tasks) {
                    foreach ($tasks as $description => $task) {
                        $task['hours'] = $task['hours'] / $multiplier;
                        $staffTimes[$sid][$pid][$date][$description] = $task;
                    }
                }
            }
        }
        return $staffTimes;
    }

    /**
     * @param $times
     * @return array
     */
    public function getTotals($times)
    {
        $totals = [];
        foreach (['time', 'profit', 'cost'] as $type) {
            foreach ($times as $pid => $time) {
                foreach ($time as $sid => $staff) {
                    foreach ($staff as $date => $tasks) {
                        foreach ($tasks as $description => $item) {
                            // get the amount
                            if ($type == 'profit') {
                                $amount = ($item['hours'] * $this->getStaffProfit($sid, $pid)) / ($this->getStaffTaxRate($sid, $pid) + 1);
                            } elseif ($type == 'cost') {
                                $amount = ($item['hours'] * $this->getStaffCost($sid, $pid)) / ($this->getStaffTaxRate($sid, $pid) + 1);
                            } else {
                                $amount = $item['hours'];
                            }
                            // init the output
                            if (!isset($totals[$type]['total']['total']))
                                $totals[$type]['total']['total'] = 0;
                            if (!isset($totals[$type]['total']['staff'][$sid]['total']))
                                $totals[$type]['total']['staff'][$sid]['total'] = 0;
                            if (!isset($totals[$type]['total']['staff'][$sid][$pid]))
                                $totals[$type]['total']['staff'][$sid][$pid] = 0;
                            if (!isset($totals[$type]['total']['project'][$pid]['total']))
                                $totals[$type]['total']['project'][$pid]['total'] = 0;
                            if (!isset($totals[$type]['total']['project'][$pid][$sid]))
                                $totals[$type]['total']['project'][$pid][$sid] = 0;
                            if (!isset($totals[$type]['total'][$date]))
                                $totals[$type]['total'][$date] = 0;
                            if (!isset($totals[$type][$date]['total']))
                                $totals[$type][$date]['total'] = 0;
                            if (!isset($totals[$type][$date]['staff'][$sid]['total']))
                                $totals[$type][$date]['staff'][$sid]['total'] = 0;
                            if (!isset($totals[$type][$date]['staff'][$sid][$pid]))
                                $totals[$type][$date]['staff'][$sid][$pid] = 0;
                            if (!isset($totals[$type][$date]['project'][$pid]['total']))
                                $totals[$type][$date]['project'][$pid]['total'] = 0;
                            if (!isset($totals[$type][$date]['project'][$pid][$sid]))
                                $totals[$type][$date]['project'][$pid][$sid] = 0;
                            // add to the output
                            $totals[$type]['total']['total'] += $amount;
                            $totals[$type]['total']['staff'][$sid]['total'] += $amount;
                            $totals[$type]['total']['staff'][$sid][$pid] += $amount;
                            $totals[$type]['total']['project'][$pid]['total'] += $amount;
                            $totals[$type]['total']['project'][$pid][$sid] += $amount;
                            $totals[$type][$date]['total'] += $amount;
                            $totals[$type][$date]['staff'][$sid]['total'] += $amount;
                            $totals[$type][$date]['staff'][$sid][$pid] += $amount;
                            $totals[$type][$date]['project'][$pid]['total'] += $amount;
                            $totals[$type][$date]['project'][$pid][$sid] += $amount;
                        }
                    }
                }
            }
        }
        return $totals;
    }

    /**
     * @param $togglData
     * @return array
     */
    public function convertTimeEntries($togglData)
    {
        $times = [];
        if ($togglData) {
            foreach ($togglData as $sid => $data) {
                foreach ($data['timeEntries'] as $timeEntry) {
                    $pid = !empty($timeEntry['pid']) ? Inflector::slug($data['projects'][$timeEntry['pid']]['name']) : 'none';
                    $description = !empty($timeEntry['description']) ? $timeEntry['description'] : 'no description';
                    $date = date('Y-m-d', strtotime($timeEntry['start']));
                    $hours = $timeEntry['duration'] > 0 ? $timeEntry['duration'] / 60 / 60 : 0;
                    $sell = $this->getStaffSell($sid, $pid);
                    $cost = $this->getStaffCost($sid, $pid);
                    $multiplier = $this->getStaffMultiplier($sid, $pid);
                    if (!isset($times[$pid][$sid][$date][$description])) {
                        $times[$pid][$sid][$date][$description] = [
                            'pid' => $pid,
                            'sid' => $sid,
                            'date' => $date,
                            'description' => $description,
                            'sell' => $sell,
                            'cost' => $cost,
                            'hours' => 0,
                        ];
                    }
                    $times[$pid][$sid][$date][$description]['hours'] += $hours * $multiplier;
                }
            }
        }
        return $times;
    }

    /**
     * @param $times
     * @return mixed
     */
    public function applyBaseRates($times)
    {
        foreach ($times as $pid => $time) {
            // remove hours that are accounted for by base hours
            $baseHours = isset($this->projects[$pid]['base_hours']) ? $this->projects[$pid]['base_hours'] : 0;
            if ($baseHours) {
                foreach ($time as $sid => $staff) {
                    foreach ($staff as $date => $tasks) {
                        foreach ($tasks as $description => $item) {
                            if ($baseHours > 0) {
                                $baseHours -= $item['hours'];
                                $times[$pid][$sid][$date][$description]['sell'] = 0;
                                $times[$pid][$sid][$date][$description]['description'] = $description . ' (base hours)';
                                if ($baseHours < 0) {
                                    $times[$pid][$sid][$date][$description]['hours'] = $item['hours'] + $baseHours;
                                    $times[$pid][$sid][$date]['base-hours-leftover: ' . $description] = [
                                        'pid' => $pid,
                                        'sid' => $sid,
                                        'date' => $date,
                                        'description' => $description,
                                        'sell' => $item['sell'],
                                        'cost' => $item['cost'],
                                        'hours' => $baseHours * -1,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
            // add base hours
            $baseRate = isset($this->projects[$pid]['base_rate']) ? $this->projects[$pid]['base_rate'] : 0;
            $baseHours = isset($this->projects[$pid]['base_hours']) ? $this->projects[$pid]['base_hours'] : 0;
            if ($baseRate) {
                $sid = key($this->staff);
                $date = date('Y-m-d');
                $description = $baseHours ? 'Base ' . $baseHours . ' hours' : 'Base rate';
                $times[$pid][$sid][$date][$description] = [
                    'pid' => $pid,
                    'sid' => $sid,
                    'date' => $date,
                    'description' => $description,
                    'sell' => $baseHours ? round($baseRate / $baseHours, 2) : $baseRate,
                    'cost' => 0,
                    'hours' => $baseHours ? $baseHours : 1,
                ];
            }
        }
        return $times;
    }

    /**
     * @param $times
     * @return mixed
     */
    public function applyCapRates($times)
    {
        foreach ($times as $pid => $time) {
            // set rate to 0 for hours over cap
            $capHours = isset($this->projects[$pid]['cap_hours']) ? $this->projects[$pid]['cap_hours'] : 0;
            if ($capHours) {
                foreach ($time as $sid => $staff) {
                    foreach ($staff as $date => $tasks) {
                        foreach ($tasks as $description => $item) {
                            $capHours -= $item['hours'];
                            if ($capHours < 0) {
                                $times[$pid][$sid][$date][$description]['hours'] += $capHours;
                                $times[$pid][$sid][$date][$description] = array(
                                    'pid' => $pid,
                                    'sid' => $sid,
                                    'date' => $date,
                                    'description' => $description,
                                    'sell' => 0,
                                    'cost' => $item['cost'],
                                    'hours' => $capHours * -1,
                                );
                                $capHours = 0;
                            }
                        }
                    }
                }
            }
        }
        return $times;
    }

    /**
     * @param $sid
     * @param null $pid
     * @return mixed
     */
    public function getStaffProfit($sid, $pid = null)
    {
        return $this->getStaffSell($sid, $pid) * $this->getStaffMultiplier($sid, $pid) - $this->getStaffCost($sid, $pid);
    }

    /**
     * @param $sid
     * @param null $pid
     * @return mixed
     */
    public function getStaffSell($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['sell'])) {
            return $this->staff[$sid]['projects'][$pid]['sell'];
        }
        return $this->staff[$sid]['sell'];
    }

    /**
     * @param $sid
     * @param null $pid
     * @return mixed
     */
    public function getStaffCost($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['cost'])) {
            return $this->staff[$sid]['projects'][$pid]['cost'];
        }
        return $this->staff[$sid]['cost'];
    }

    /**
     * @param $sid
     * @param null $pid
     * @return mixed
     */
    public function getStaffMultiplier($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['multiplier'])) {
            return $this->staff[$sid]['projects'][$pid]['multiplier'];
        }
        return isset($this->staff[$sid]['multiplier']) ? $this->staff[$sid]['multiplier'] : 1;
    }

    /**
     * @param $sid
     * @param null $pid
     * @return mixed
     */
    public function getStaffTaxRate($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['tax_rate'])) {
            return $this->staff[$sid]['projects'][$pid]['tax_rate'];
        }
        return isset($this->staff[$sid]['tax_rate']) ? $this->staff[$sid]['tax_rate'] : 0;
    }

    /**
     * @param $pid
     * @return int
     */
    public function getProjectTaxRate($pid)
    {
        return isset($this->projects[$pid]['tax_rate']) ? $this->projects[$pid]['tax_rate'] : 0;
    }

}
