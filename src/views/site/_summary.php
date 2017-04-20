<?php

/**
 * @var View $this
 * @var array $totals
 */

use app\components\Helper;
use yii\web\View;

if (!$totals) {
    return;
}
?>

<h2>
    <?php
    echo Yii::t('app', 'Summary') . ': ';
    echo Helper::formatHours($totals['time']['total']['total']);
    echo ' = $';
    echo number_format($totals['sell']['total']['total'] - $totals['cost']['total']['total'], 2);
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
                    <strong><?= Yii::$app->timeSheet->staff[$sid]['name'] ?></strong>
                </div>
                <div class="col-md-10">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">project</th>
                            <th width="15%">hours</th>
                            <th width="15%">
                                C&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Cost Ex GST"></i>
                            </th>
                            <th width="15%">
                                S&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Sell Ex GST"></i>
                            </th>
                            <th width="15%">
                                P&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Profit Ex GST"></i>
                            </th>
                        </tr>
                        <?php
                        foreach ($projects as $pid => $hours) {
                            if ($pid == 'total') continue;
                            $staffTaxRate = Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1;
                            $projectTaxRate = Yii::$app->timeSheet->getProjectTaxRate($pid) + 1;
                            $staffCost = Yii::$app->timeSheet->getStaffCost($sid, $pid) / Yii::$app->timeSheet->getStaffMultiplier($sid, $pid);
                            $staffSell = Yii::$app->timeSheet->getStaffSell($sid, $pid);
                            $cost = ($hours * $staffCost) / $staffTaxRate;
                            $sell = ($hours * $staffSell) / $projectTaxRate;
                            ?>
                            <tr>
                                <td><?= Yii::$app->timeSheet->projects[$pid]['name'] ?></td>
                                <td class="text-right">
                                    <?= Helper::formatHours($hours) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($cost, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($sell, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($sell - $cost, 2) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right">
                                <?= Helper::formatHours($totals['time']['total']['staff'][$sid]['total']) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell']['total']['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell']['total']['staff'][$sid]['total'] - $totals['cost']['total']['staff'][$sid]['total'], 2) ?>
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
                    <strong><?= Yii::$app->timeSheet->projects[$pid]['name'] ?></strong>
                </div>
                <div class="col-md-10">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">staff</th>
                            <th width="15%">hours</th>
                            <th width="15%">
                                C&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Cost Ex GST"></i>
                            </th>
                            <th width="15%">
                                S&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Sell Ex GST"></i>
                            </th>
                            <th width="15%">
                                P&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Profit Ex GST"></i>
                            </th>
                        </tr>
                        <?php
                        foreach ($staffs as $sid => $hours) {
                            if ($sid == 'total') continue;
                            $staffTaxRate = Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1;
                            $projectTaxRate = Yii::$app->timeSheet->getProjectTaxRate($pid) + 1;
                            $staffCost = Yii::$app->timeSheet->getStaffCost($sid, $pid) / Yii::$app->timeSheet->getStaffMultiplier($sid, $pid);
                            $staffSell = Yii::$app->timeSheet->getStaffSell($sid, $pid);
                            $cost = ($hours * $staffCost) / $staffTaxRate;
                            $sell = ($hours * $staffSell) / $projectTaxRate;
                            ?>
                            <tr>
                                <td><?= Yii::$app->timeSheet->staff[$sid]['name'] ?></td>
                                <td class="text-right">
                                    <?= Helper::formatHours($hours) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($cost, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($sell, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($sell - $cost, 2) ?>
                                </td>
                            </tr>
                            <?php
                        }
                        ?>
                        <tr>
                            <th>Total</th>
                            <th class="text-right">
                                <?= Helper::formatHours($totals['time']['total']['project'][$pid]['total']) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell']['total']['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell']['total']['project'][$pid]['total'] - $totals['cost']['total']['project'][$pid]['total'], 2) ?>
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
