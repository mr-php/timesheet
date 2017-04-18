<?php

/**
 * @var View $this
 * @var array $totals
 * @var array $daily
 * @var string $date
 */

use app\components\Helper;
use yii\web\View;

/** @var \app\components\TimeSheet $timeSheet */
$timeSheet = Yii::$app->timeSheet;
?>

<h3>
    <?php
    echo date('D, j M', strtotime($date));
    echo ': ';
    echo Helper::formatHours($daily['total']);
    echo ' = $';
    echo number_format($totals['profit'][$date]['total'], 2);
    ?>
</h3>
<div class="row">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-user"></i> Hours by Staff</h4>
        <?php
        foreach ($daily['staff'] as $sid => $projects) {
            if ($sid == 'total') continue;
            ?>
            <div class="row">
                <div class="col-md-2">
                    <strong><?= Yii::$app->timeSheet->staff[$sid]['name'] ?></strong>
                </div>
                <div class="col-md-10">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">profile</th>
                            <th width="15%">hours</th>
                            <th width="15%">C&nbsp;<i class="glyphicon glyphicon-info-sign" title="Cost Ex GST"></i></th>
                            <th width="15%">S&nbsp;<i class="glyphicon glyphicon-info-sign" title="Sell Ex GST"></i></th>
                            <th width="15%">P&nbsp;<i class="glyphicon glyphicon-info-sign" title="Profit Ex GST"></i></th>
                        </tr>
                        <?php
                        foreach ($projects as $pid => $hours) {
                            if ($pid == 'total') continue;
                            ?>
                            <tr>
                                <td><?= Yii::$app->timeSheet->projects[$pid]['name'] ?></td>
                                <td class="text-right">
                                    <?= number_format($hours, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $timeSheet->getStaffCost($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * ($timeSheet->getStaffCost($sid, $pid) + $timeSheet->getStaffProfit($sid, $pid))) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $timeSheet->getStaffProfit($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right">
                                <?= number_format($daily['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost'][$date]['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost'][$date]['staff'][$sid]['total'] + $totals['profit'][$date]['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['profit'][$date]['staff'][$sid]['total'], 2) ?>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-briefcase"></i> Hours by Project</h4>
        <?php
        foreach ($daily['project'] as $pid => $staffs) {
            if ($pid == 'total') continue;
            ?>
            <div class="row">
                <div class="col-md-2">
                    <strong><?= Yii::$app->timeSheet->projects[$pid]['name'] ?></strong>
                </div>
                <div class="col-md-10">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">staff</th>
                            <th width="15%">hours</th>
                            <th width="15%">C&nbsp;<i class="glyphicon glyphicon-info-sign" title="Cost Ex GST"></i></th>
                            <th width="15%">S&nbsp;<i class="glyphicon glyphicon-info-sign" title="Sell Ex GST"></i></th>
                            <th width="15%">P&nbsp;<i class="glyphicon glyphicon-info-sign" title="Profit Ex GST"></i></th>
                        </tr>
                        <?php
                        foreach ($staffs as $sid => $hours) {
                            if ($sid == 'total') continue;
                            ?>
                            <tr>
                                <td><?= Yii::$app->timeSheet->staff[$sid]['name'] ?></td>
                                <td class="text-right">
                                    <?= number_format($hours, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $timeSheet->getStaffCost($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * ($timeSheet->getStaffCost($sid, $pid) + $timeSheet->getStaffProfit($sid, $pid))) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $timeSheet->getStaffProfit($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right">
                                <?= number_format($daily['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost'][$date]['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost'][$date]['project'][$pid]['total'] + $totals['profit'][$date]['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['profit'][$date]['project'][$pid]['total'], 2) ?>
                            </th>
                        </tr>
                    </table>
                </div>
            </div>
            <?php
        }
        ?>
    </div>
</div>
