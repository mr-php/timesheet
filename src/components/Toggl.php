<?php

namespace app\components;

use AJT\Toggl\TogglClient;
use Yii;
use yii\base\Component;

/**
 * Class Toggl
 * @package app\components
 */
class Toggl extends Component
{

    /**
     * @var string
     */
    public $startDate;

    /**
     * @var string
     */
    public $endDate;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        $settings = ['startDate', 'endDate'];
        foreach ($settings as $key) {
            $value = Yii::$app->settings->get('TogglSettingsForm', $key);
            if ($value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param $staffList
     * @return array
     */
    public function import($staffList)
    {
        $toggl = [];
        foreach ($staffList as $sid => $staff) {
            $client = TogglClient::factory([
                'api_key' => $staff['toggl_api_key'],
                'apiVersion' => 'v8',
            ]);
            $toggl[$sid]['workspaces'] = $client->getWorkspaces();
            foreach ($toggl[$sid]['workspaces'] as $workspace) {
                foreach ($client->getProjects(['id' => $workspace['id']]) as $project) {
                    $toggl[$sid]['projects'][$project['id']] = $project;
                }
            }
            $toggl[$sid]['clients'] = $client->getClients();
            $toggl[$sid]['current'] = $client->GetCurrentTimeEntry();
            $params = [];
            if ($this->startDate)
                $params['start_date'] = date('c', strtotime('00:00', strtotime($this->startDate)));
            if ($this->endDate)
                $params['end_date'] = date('c', strtotime('00:00', strtotime($this->endDate)));
            $toggl[$sid]['timeEntries'] = $client->getTimeEntries($params);
        }
        return $toggl;
    }

}