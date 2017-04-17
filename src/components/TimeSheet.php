<?php

namespace app\components;

use AJT\Toggl\TogglClient;
use yii\base\Component;
use yii\helpers\Inflector;

class TimeSheet extends Component
{

    public $staff = [];

    public $projects = [];

    public function init()
    {
        $this->initToggl();
        parent::init();
    }

    public function getTimes()
    {
        $times = $this->convertTimeEntries();
        $times = $this->applyBaseRates($times);
        $times = $this->applyCapRates($times);
        return $times;
    }

    public function getTotals($times)
    {
        $totals = [];
        foreach (['time', 'profit', 'cost'] as $type) {
            foreach ($times as $pid => $time) {
                foreach ($time as $sid => $staff) {
                    foreach ($staff as $date => $tasks) {
                        foreach ($tasks as $description => $item) {
                            // get the amount
                            if ($type == 'time') {
                                $amount = $item['hours'];
                            }
                            if ($type == 'profit') {
                                $amount = ($item['hours'] * $this->getStaffProfit($sid, $pid)) / ($this->getStaffTaxRate($sid, $pid) + 1);
                            }
                            if ($type == 'cost') {
                                $amount = ($item['hours'] * $this->getStaffCost($sid, $pid)) / ($this->getStaffTaxRate($sid, $pid) + 1);
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

    public function convertTimeEntries()
    {
        $times = [];
        foreach ($this->_toggl as $sid => $toggl) {
            foreach ($toggl['timeEntries'] as $timeEntry) {
                $pid = Inflector::slug($toggl['projects'][$timeEntry['pid']]['name']);
                $description = $timeEntry['description'];
                $date = date('Y-m-d', strtotime($timeEntry['start']));
                $hours = $timeEntry['duration'] > 0 ? $timeEntry['duration'] / 60 / 60 : 0;
                $rate = $this->getStaffRate($sid, $pid);
                $multiplier = $this->getStaffMultiplier($sid, $pid);
                if (!isset($times[$pid][$sid][$date][$description])) {
                    $times[$pid][$sid][$date][$description] = [
                        'pid' => $pid,
                        'sid' => $sid,
                        'date' => $date,
                        'description' => $description,
                        'rate' => $rate,
                        'hours' => 0,
                    ];
                }
                $times[$pid][$sid][$date][$description]['hours'] += $hours * $multiplier;
            }
        }
        return $times;
    }

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
                                $times[$pid][$sid][$date][$description]['rate'] = 0;
                                if ($baseHours < 0) {
                                    $times[$pid][$sid][$date][$description]['hours'] = $item['hours'] + $baseHours;
                                    $times[$pid][$sid][$date]['Base Hours: ' . $description] = [
                                        'pid' => $pid,
                                        'sid' => $sid,
                                        'date' => $date,
                                        'description' => 'Base Hours:' . $description,
                                        'rate' => $item['rate'],
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
            if ($baseRate) {
                array_unshift($times[$pid][0][date('Y-m-d')], array(
                    'pid' => $pid,
                    'sid' => 0,
                    'date' => date('Y-m-d'),
                    'description' => 'Base ' . $baseHours . ' hours',
                    'rate' => $baseRate / $baseHours,
                    'hours' => $baseHours,
                ));
            }
        }
        return $times;
    }

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
                                if (!$times[$pid][$sid][$date][$description]['hours']) {
                                    unset($times[$pid][$sid][$date][$description]);
                                }
                                $times[$pid][$sid][$date][$description] = array(
                                    'pid' => $pid,
                                    'sid' => $sid,
                                    'date' => $date,
                                    'description' => $description,
                                    'rate' => 0,
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

    public function getStaffProfit($sid, $pid = null)
    {
        return $this->getStaffRate($sid, $pid) * $this->getStaffMultiplier($sid, $pid) - $this->getStaffCost($sid, $pid);
    }

    public function getStaffRate($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['rate'])) {
            return $this->staff[$sid]['projects'][$pid]['rate'];
        }
        return $this->staff[$sid]['rate'];
    }

    public function getStaffCost($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['cost'])) {
            return $this->staff[$sid]['projects'][$pid]['cost'];
        }
        return $this->staff[$sid]['cost'];
    }

    public function getStaffMultiplier($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['multiplier'])) {
            return $this->staff[$sid]['projects'][$pid]['multiplier'];
        }
        return $this->staff[$sid]['multiplier'];
    }

    public function getStaffTaxRate($sid, $pid = null)
    {
        if ($pid && isset($this->staff[$sid]['projects'][$pid]['tax_rate'])) {
            return $this->staff[$sid]['projects'][$pid]['tax_rate'];
        }
        return $this->staff[$sid]['tax_rate'];
    }

    private $_toggl;

    private function initToggl()
    {
        $this->_toggl = [];
        foreach ($this->staff as $sid => $staff) {
            $toggl = TogglClient::factory([
                'api_key' => $staff['toggl_api_key'],
                'apiVersion' => 'v8',
            ]);
            $this->_toggl[$sid]['workspaces'] = $toggl->getWorkspaces();
            foreach ($this->_toggl[$sid]['workspaces'] as $workspace) {
                foreach ($toggl->getProjects(['id' => $workspace['id']]) as $project) {
                    $this->_toggl[$sid]['projects'][$project['id']] = $project;
                }
            }
            $this->_toggl[$sid]['clients'] = $toggl->getClients();
            $this->_toggl[$sid]['timeEntries'] = $toggl->getTimeEntries([
                //'start_date' => date('c', strtotime('00:00 -1week')),
                //'end_date' => date('c', strtotime('00:00 -0week')),
            ]);
        }
    }

}