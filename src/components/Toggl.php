<?php

namespace app\components;

use AJT\Toggl\TogglClient;
use Yii;
use yii\base\Component;

/**
 * Toggl
 * @package app\components
 */
class Toggl extends Component
{

    public static function import($staffList)
    {
        $toggl = [];
        $lastInvoiceDate = Yii::$app->cache->get('lastInvoiceDate') ?: 'last monday';
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
            $toggl[$sid]['timeEntries'] = $client->getTimeEntries([
                'start_date' => date('c', strtotime('00:00', strtotime($lastInvoiceDate))),
            ]);
            $toggl[$sid]['current'] = $client->GetCurrentTimeEntry();
        }
        return $toggl;
    }

}