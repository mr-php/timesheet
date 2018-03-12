<?php

namespace app\components;

use Upwork\API\Routers\Reports\Time;
use Yii;
use yii\base\Component;

/**
 * Class Upwork
 * @package app\components
 */
class Upwork extends Component
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
            $value = Yii::$app->settings->get('UpworkSettingsForm', $key);
            if ($value) {
                $this->$key = $value;
            }
        }
    }

    /**
     * @param $staffList
     * @return array
     * @throws \Exception
     */
    public function import($staffList)
    {
        $upwork = [];
        foreach ($staffList as $sid => $staff) {

            $client = '';
            $reports = new Time($client);
            $freelancerId = "john_freelancer";
            $params = array(
                "tq" => "SELECT worked_on, assignment_team_id, hours, task, memo WHERE worked_on > '2009-10-01' AND worked_on <= '2009-10-31'"
            );
            $reports->getByFreelancerFull($freelancerId, $params);


            //$client = TogglClient::factory([
            //    'api_key' => $staff['toggl_api_key'],
            //    'apiVersion' => 'v8',
            //]);
            //$toggl[$sid]['workspaces'] = $client->getWorkspaces();
            //foreach ($toggl[$sid]['workspaces'] as $workspace) {
            //    foreach ($client->getProjects(['id' => $workspace['id']]) as $project) {
            //        $toggl[$sid]['projects'][$project['id']] = $project;
            //    }
            //}
            //$toggl[$sid]['clients'] = $client->getClients();
            //
            //$current = $client->GetCurrentTimeEntry();
            //if (isset($staff['toggl_workspace_id']) && (!isset($current['wid']) || $staff['toggl_workspace_id'] != $current['wid'])) {
            //    $toggl[$sid]['current'] = false;
            //} else {
            //    $toggl[$sid]['current'] = $current;
            //}
            //
            //$params = [];
            //if ($this->startDate)
            //    $params['start_date'] = date('c', strtotime('00:00', strtotime($this->startDate)));
            //if ($this->endDate)
            //    $params['end_date'] = date('c', strtotime('00:00', strtotime($this->endDate)));
            //$toggl[$sid]['timeEntries'] = [];
            //foreach ($client->getTimeEntries($params) as $timeEntry) {
            //    if (isset($staff['toggl_workspace_id']) && (!isset($timeEntry['wid']) || $staff['toggl_workspace_id'] != $timeEntry['wid'])) {
            //        continue;
            //    }
            //    $toggl[$sid]['timeEntries'][] = $timeEntry;
            //}
        }
        return $upwork;
    }

}