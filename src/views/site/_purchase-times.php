<?php

/**
 * @var View $this
 * @var array $times
 */

use app\components\Helper;
use yii\web\View;

$contents = [];
$total = 0;
foreach ($times as $pid => $dates) {
    $project = Yii::$app->timeSheet->projects[$pid];
    $projectTotal = 0;
    $contents[] = '================================';
    $contents[] = "Hours per Task for {$project['name']}";
    $contents[] = '================================';
    foreach ($dates as $date => $tasks) {
        foreach ($tasks as $task) {
            $projectTotal += round($task['hours'],2);
            $total += round($task['hours'],2);
        }
    }
    foreach ($dates as $date => $tasks) {
        $dayTotal = 0;
        $dayTasks = [];
        foreach ($tasks as $task) {
            $dayTotal += round($task['hours'],2);
            $dayTasks[] = Helper::formatHours(round($task['hours'],2)) . ' - ' . htmlspecialchars($task['description']);
        }
        $contents[] = date('Y-m-d - l', strtotime($date));
        $contents[] = '--------------------------------';
        $contents[] = implode("\r\n", $dayTasks);
        $contents[] = '--------------------------------';
        $contents[] = Helper::formatHours($dayTotal) . " - day total";
        $contents[] = '================================';
    }
    $contents[] = Helper::formatHours($projectTotal) . " - Total for {$project['name']}";
    $contents[] = '================================';
    $contents[] = '';
}
$contents[] = '================================';
$contents[] = Helper::formatHours($total) . " - Grand Total";
$contents[] = '================================';
echo implode("\r\n", $contents);