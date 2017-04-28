<?php

/**
 * @var View $this
 * @var array $totals
 */

use app\components\Helper;
use cebe\gravatar\Gravatar;
use yii\web\View;

if (!$totals) {
    return;
}
?>

<h2>
    <?php
    echo Yii::t('app', 'Summary') . ': ';
    echo Helper::formatHours($totals['time']['total']['total']);
    echo ' | ';
    echo number_format($totals['sell']['total']['total'], 0);
    echo ' - ';
    echo number_format($totals['cost']['total']['total'], 0);
    echo ' = ';
    echo '$' . number_format($totals['sell']['total']['total'] - $totals['cost']['total']['total'], 0);
    ?>
</h2>

<div class="row">
    <div class="col-md-6">
        <h3><i class="glyphicon glyphicon-user"></i> Hours by Staff per Project</h3>
        <?php
        foreach ($totals['time']['total']['staff'] as $sid => $projects) {
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
                            <th width="28%">project</th>
                            <th width="18%" class="text-right">hours</th>
                            <th width="18%" class="text-right">
                                S&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Sell Ex GST"></i>
                            </th>
                            <th width="18%" class="text-right">
                                C&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Cost Ex GST"></i>
                            </th>
                            <th width="18%" class="text-right">
                                P&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Profit Ex GST"></i>
                            </th>
                        </tr>
                        <?php
                        foreach ($projects as $pid => $hours) {
                            if ($pid == 'total') continue;
                            $project = Yii::$app->timeSheet->projects[$pid];
                            $staffTaxRate = Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1;
                            $projectTaxRate = Yii::$app->timeSheet->getProjectTaxRate($pid) + 1;
                            $cost = $totals['cost']['total']['staff'][$sid][$pid];
                            $sell = $totals['sell']['total']['staff'][$sid][$pid];
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
                                <?= Helper::formatHours($totals['time']['total']['staff'][$sid]['total']) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell']['total']['staff'][$sid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['staff'][$sid]['total'], 2) ?>
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
        <h3><i class="glyphicon glyphicon-briefcase"></i> Hours by Project per Staff</h3>
        <?php
        foreach ($totals['time']['total']['project'] as $pid => $staffs) {
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
                            <th width="28%">staff</th>
                            <th width="18%" class="text-right">hours</th>
                            <th width="18%" class="text-right">
                                S&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Sell Ex GST"></i>
                            </th>
                            <th width="18%" class="text-right">
                                C&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Cost Ex GST"></i>
                            </th>
                            <th width="18%" class="text-right">
                                P&nbsp;<i class="glyphicon glyphicon-info-sign" data-toggle="tooltip" title="Profit Ex GST"></i>
                            </th>
                        </tr>
                        <?php
                        foreach ($staffs as $sid => $hours) {
                            if ($sid == 'total') continue;
                            $staff = Yii::$app->timeSheet->staff[$sid];
                            $staffTaxRate = Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1;
                            $projectTaxRate = Yii::$app->timeSheet->getProjectTaxRate($pid) + 1;
                            $cost = $totals['cost']['total']['project'][$pid][$sid];
                            $sell = $totals['sell']['total']['project'][$pid][$sid];
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
                                <?= Helper::formatHours($totals['time']['total']['project'][$pid]['total']) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['sell']['total']['project'][$pid]['total'], 2) ?>
                            </th>
                            <th class="text-right">
                                <?= '$' . number_format($totals['cost']['total']['project'][$pid]['total'], 2) ?>
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
