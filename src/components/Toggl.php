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
        $data = [];
        $lastInvoiceDate = Yii::$app->cache->get('lastInvoiceDate') ?: 'last monday';
        foreach ($staffList as $sid => $staff) {
            $toggl = TogglClient::factory([
                'api_key' => $staff['toggl_api_key'],
                'apiVersion' => 'v8',
            ]);
            $data[$sid]['workspaces'] = $toggl->getWorkspaces();
            foreach ($data[$sid]['workspaces'] as $workspace) {
                foreach ($toggl->getProjects(['id' => $workspace['id']]) as $project) {
                    $data[$sid]['projects'][$project['id']] = $project;
                }
            }
            $data[$sid]['clients'] = $toggl->getClients();
            $data[$sid]['timeEntries'] = $toggl->getTimeEntries([
                'start_date' => date('c', strtotime('00:00', strtotime($lastInvoiceDate))),
            ]);
        }
        return $data;
    }

}