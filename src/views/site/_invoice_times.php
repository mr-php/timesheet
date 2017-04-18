<?php

/**
 * @var View $this
 * @var array $times
 */

use yii\web\View;

$contents = [];
$total = 0;
foreach ($times as $sid => $dates) {
    $staffTotal = 0;
    $contents[] = '================================';
    $contents[] = "Hours per Task by {$sid}";
    $contents[] = '================================';
    foreach ($dates as $date => $tasks) {
        foreach ($tasks as $task) {
            $staffTotal += $task['hours'];
            $total += $task['hours'];
        }
    }
    foreach ($dates as $date => $tasks) {
        $dayTotal = 0;
        $dayTasks = [];
        foreach ($tasks as $task) {
            $dayTotal += $task['hours'];
            $dayTasks[] = number_format($task['hours'], 2) . ' - ' . htmlspecialchars($task['description']);
        }
        $contents[] = date('Y-m-d - l', strtotime($date));
        $contents[] = '--------------------------------';
        $contents[] = implode("\r\n", $dayTasks);
        $contents[] = '--------------------------------';
        $contents[] = number_format($dayTotal, 2) . " - day total";
        $contents[] = '================================';
    }
    $contents[] = number_format($staffTotal, 2) . " - Total for {$sid}";
    $contents[] = '================================';
    $contents[] = '';
}
$contents[] = '================================';
$contents[] = number_format($total, 2) . " - Grand Total";
$contents[] = '================================';
echo implode("\r\n", $contents);