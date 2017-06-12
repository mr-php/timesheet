<?php

/**
 * @var View $this
 * @var array $times
 */

use app\components\Helper;
use yii\web\View;

$contents = [];
$total = 0;
foreach ($times as $sid => $dates) {
    $staff = Yii::$app->timeSheet->staff[$sid];
    $staffTotal = 0;
    $contents[] = '================================';
    $contents[] = "Hours per Task by {$staff['name']}";
    $contents[] = '================================';
    foreach ($dates as $date => $tasks) {
        foreach ($tasks as $task) {
            $staffTotal += round($task['hours'],2);
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
    $contents[] = Helper::formatHours($staffTotal) . " - Total for {$staff['name']}";
    $contents[] = '================================';
    $contents[] = '';
}
$contents[] = '================================';
$contents[] = Helper::formatHours($total) . " - Grand Total";
$contents[] = '================================';
echo implode("\r\n", $contents);