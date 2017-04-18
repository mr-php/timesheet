<?php

/**
 * @var View $this
 * @var array $totals
 */

use yii\web\View;

/** @var \app\components\TimeSheet $timeSheet */
$timeSheet = Yii::$app->timeSheet;

if (!$totals){
    return;
}
?>

<h2>
    <?php
    echo Yii::t('app', 'Summary') . ': ';
    echo number_format($totals['time']['total']['total'], 2);
    echo 'h = $';
    echo number_format($totals['profit']['total']['total'], 2);
    ?>
</h2>

<div class="row">
    <div class="col-md-6">
        <h3><i class="glyphicon glyphicon-user"></i> Hours by Staff</h3>
        <?php
        foreach ($totals['time']['total']['staff'] as $sid => $projects) {
            if ($sid == 'total') continue;
            ?>
            <div class="row">
                <div class="col-md-2">
                    <strong><?= $sid ?></strong>
                </div>
                <div class="col-md-10">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">project</th>
                            <th width="15%">hours</th>
                            <th width="15%">C&nbsp;<i class="glyphicon glyphicon-info-sign"
                                                      title="Cost Ex GST"></i></th>
                            <th width="15%">S&nbsp;<i class="glyphicon glyphicon-info-sign"
                                                      title="Sell Ex GST"></i></th>
                            <th width="15%">P&nbsp;<i class="glyphicon glyphicon-info-sign"
                                                      title="Profit Ex GST"></i></th>
                        </tr>
                        <?php
                        foreach ($projects as $pid => $hours) {
                            if ($pid == 'total') continue;
                            $staffTaxRate = $timeSheet->getStaffTaxRate($sid, $pid);
                            $staffCost = $timeSheet->getStaffCost($sid, $pid);
                            $staffProfit = $timeSheet->getStaffProfit($sid, $pid);
                            ?>
                            <tr>
                                <td><?= $pid ?></td>
                                <td class="text-right">
                                    <?= number_format($hours, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $staffCost) / ($staffTaxRate + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * ($staffCost + $staffProfit)) / ($staffTaxRate + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $staffProfit) / ($staffTaxRate + 1), 2) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right">
                                <?= number_format($totals['time']['total']['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['staff'][$sid]['total'] + $totals['profit']['total']['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['profit']['total']['staff'][$sid]['total'], 2) ?>
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
        <h3><i class="glyphicon glyphicon-briefcase"></i> Hours by Project</h3>
        <?php
        foreach ($totals['time']['total']['project'] as $pid => $staffs) {
            if ($pid == 'total') continue;
            ?>
            <div class="row">
                <div class="col-md-2">
                    <strong><?= $pid ?></strong>
                </div>
                <div class="col-md-10">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">staff</th>
                            <th width="15%">hours</th>
                            <th width="15%">C&nbsp;<i class="glyphicon glyphicon-info-sign"
                                                      title="Cost Ex GST"></i></th>
                            <th width="15%">S&nbsp;<i class="glyphicon glyphicon-info-sign"
                                                      title="Sell Ex GST"></i></th>
                            <th width="15%">P&nbsp;<i class="glyphicon glyphicon-info-sign"
                                                      title="Profit Ex GST"></i></th>
                        </tr>
                        <?php
                        foreach ($staffs as $sid => $hours) {
                            if ($sid == 'total') continue;
                            $staffTaxRate = $timeSheet->getStaffTaxRate($sid, $pid);
                            $staffCost = $timeSheet->getStaffCost($sid, $pid);
                            $staffProfit = $timeSheet->getStaffProfit($sid, $pid);
                            ?>
                            <tr>
                                <td><?= $sid ?></td>
                                <td class="text-right">
                                    <?= number_format($hours, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $staffCost) / ($staffTaxRate + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * ($staffCost + $staffProfit)) / ($staffTaxRate + 1), 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format(($hours * $staffProfit) / ($staffTaxRate + 1), 2) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right">
                                <?= number_format($totals['time']['total']['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['project'][$pid]['total'] + $totals['profit']['total']['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['profit']['total']['project'][$pid]['total'], 2) ?>
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
