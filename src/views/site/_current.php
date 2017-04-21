<?php

/**
 * @var View $this
 * @var array $toggl
 */

use yii\bootstrap\Alert;
use yii\web\View;

if (!$toggl) {
    return;
}
foreach ($toggl as $sid => $data) {
    if (isset($data['current']['id'])) {
        $staff = Yii::$app->timeSheet->staff[$sid];
        if (isset($staff['toggl_workflow_id']) && (!isset($data['current']['wid']) || $data['current']['wid'] != $staff['toggl_workflow_id'])) {
            continue;
        }
        echo Alert::widget([
            'body' => Yii::t('app', '{sid} has a current timer: {description}', [
                'sid' => Yii::$app->timeSheet->staff[$sid]['name'],
                'description' => isset($data['current']['description']) ? $data['current']['description'] : 'no description',
            ]),
            'options' => ['class' => 'alert-danger'],
            'closeButton' => false,
        ]);
    }
}
