<?php

/**
 * @var View $this
 * @var array $totals
 * @var array $daily
 * @var string $date
 */

use app\components\Helper;
use cebe\gravatar\Gravatar;
use yii\web\View;

?>

<h3>
    <?php
    echo date('D, j M', strtotime($date));
    echo ': ';
    echo Helper::formatHours($daily['total']);
    echo ' | ';
    echo number_format($totals['sell'][$date]['total'], 2);
    echo ' - ';
    echo number_format($totals['cost'][$date]['total'], 2);
    echo ' = ';
    echo '$' . number_format($totals['sell'][$date]['total'] - $totals['cost'][$date]['total'], 2);
    ?>
</h3>
<div class="row">
    <div class="col-md-6">
        <h4><i class="glyphicon glyphicon-user"></i> Hours by Staff</h4>
        <?php
        foreach ($daily['staff'] as $sid => $projects) {
            if ($sid == 'total') continue;
            $staff = Yii::$app->timeSheet->staff[$sid];
            ?>
            <div class="row">
                <div class="col-md-3">
                    <?= Gravatar::widget([
                        'email' => $staff['email'],
                        'options' => [
                            'alt' => $staff['name'],
                            'class' => 'img-circle',
                        ],
                        'size' => 16,
                        'defaultImage' => 'wavatar',
                    ]) ?>
                    <strong><?= $staff['name'] ?></strong>
                </div>
                <div class="col-md-9">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">profile</th>
                            <th width="15%">hours</th>
                            <th width="15%">
                                S&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Sell Ex GST"></i>
                            </th>
                            <th width="15%">
                                C&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Cost Ex GST"></i>
                            </th>
                            <th width="15%">
                                P&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Profit Ex GST"></i>
                            </th>
                        </tr>
                        <?php
                        foreach ($projects as $pid => $hours) {
                            if ($pid == 'total') continue;
                            $project = Yii::$app->timeSheet->projects[$pid];
                            $staffTaxRate = Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1;
                            $projectTaxRate = Yii::$app->timeSheet->getProjectTaxRate($pid) + 1;
                            $staffCost = Yii::$app->timeSheet->getStaffCost($sid, $pid) / Yii::$app->timeSheet->getStaffMultiplier($sid, $pid);
                            $staffSell = Yii::$app->timeSheet->getStaffSell($sid, $pid);
                            $cost = ($hours * $staffCost) / $staffTaxRate;
                            $sell = ($hours * $staffSell) / $projectTaxRate;
                            ?>
                            <tr>
                                <td>
                                    <?= Gravatar::widget([
                                        'email' => $project['email'],
                                        'options' => [
                                            'alt' => $project['name'],
                                            'class' => 'img-circle',
                                        ],
                                        'size' => 16,
                                        'defaultImage' => 'wavatar',
                                    ]) ?>
                                    <?= $project['name'] ?>
                                </td>
                                <td class="text-right">
                                    <?= Helper::formatHours($hours) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($sell, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($cost, 2) ?>
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
                                <?= Helper::formatHours($daily['staff'][$sid]['total']) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell'][$date]['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost'][$date]['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell'][$date]['staff'][$sid]['total'] - $totals['cost'][$date]['staff'][$sid]['total'], 2) ?>
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
            $project = Yii::$app->timeSheet->projects[$pid];
            ?>
            <div class="row">
                <div class="col-md-3">
                    <?= Gravatar::widget([
                        'email' => $project['email'],
                        'options' => [
                            'alt' => $project['name'],
                            'class' => 'img-circle',
                        ],
                        'size' => 16,
                        'defaultImage' => 'wavatar',
                    ]) ?>
                    <strong><?= $project['name'] ?></strong>
                </div>
                <div class="col-md-9">
                    <table class="table table-condensed">
                        <tr>
                            <th width="40%">staff</th>
                            <th width="15%">hours</th>
                            <th width="15%">
                                S&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Sell Ex GST"></i>
                            </th>
                            <th width="15%">
                                C&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Cost Ex GST"></i>
                            </th>
                            <th width="15%">
                                P&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Profit Ex GST"></i>
                            </th>
                        </tr>
                        <?php
                        foreach ($staffs as $sid => $hours) {
                            if ($sid == 'total') continue;
                            $staff = Yii::$app->timeSheet->staff[$sid];
                            $staffTaxRate = Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1;
                            $projectTaxRate = Yii::$app->timeSheet->getProjectTaxRate($pid) + 1;
                            $staffCost = Yii::$app->timeSheet->getStaffCost($sid, $pid) / Yii::$app->timeSheet->getStaffMultiplier($sid, $pid);
                            $staffSell = Yii::$app->timeSheet->getStaffSell($sid, $pid);
                            $cost = ($hours * $staffCost) / $staffTaxRate;
                            $sell = ($hours * $staffSell) / $projectTaxRate;
                            ?>
                            <tr>
                                <td>
                                    <?= Gravatar::widget([
                                        'email' => $staff['email'],
                                        'options' => [
                                            'alt' => $staff['name'],
                                            'class' => 'img-circle',
                                        ],
                                        'size' => 16,
                                        'defaultImage' => 'wavatar',
                                    ]) ?>
                                    <?= $staff['name'] ?>
                                </td>
                                <td class="text-right">
                                    <?= Helper::formatHours($hours) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($sell, 2) ?>
                                </td>
                                <td class="text-right">
                                    <?= number_format($cost, 2) ?>
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
                                <?= Helper::formatHours($daily['project'][$pid]['total']) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell'][$date]['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost'][$date]['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell'][$date]['project'][$pid]['total'] - $totals['cost'][$date]['project'][$pid]['total'], 2) ?>
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