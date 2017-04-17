<?php

/*
 * @link http://www.diemeisterei.de/
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/* @var $this yii\web\View */

$this->title .= 'Home';

/** @var \app\components\TimeSheet $timeSheet */
$timeSheet = Yii::$app->timeSheet;
$times = $timeSheet->getTimes();
$totals = $timeSheet->getTotals($times);
?>

<div class="site-index">
    <div class="container">
        <h2>
            <?php
            echo 'Total: ';
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
                            <?= $sid ?>
                        </div>
                        <div class="col-md-10">
                            <table class="table table-striped table-condensed">
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
                                        <td><?php echo $pid; ?></td>
                                        <td class="text-right">
                                            <?php echo number_format($hours, 2); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format(($hours * $staffCost) / ($staffTaxRate + 1), 2); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format(($hours * ($staffCost + $staffProfit)) / ($staffTaxRate + 1), 2); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format(($hours * $staffProfit) / ($staffTaxRate + 1), 2); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right">
                                        <?php echo number_format($totals['time']['total']['staff'][$sid]['total'], 2); ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo '$' . number_format($totals['cost']['total']['staff'][$sid]['total'], 2); ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo '$' . number_format($totals['cost']['total']['staff'][$sid]['total'] + $totals['profit']['total']['staff'][$sid]['total'], 2); ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo '$' . number_format($totals['profit']['total']['staff'][$sid]['total'], 2); ?>
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
                            <?= $pid ?>
                        </div>
                        <div class="col-md-10">
                            <table class="table table-striped table-condensed">
                                <tr>
                                    <th width="50%">staff</th>
                                    <th width="25%">hours</th>
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
                                        <td><?php echo $sid; ?></td>
                                        <td class="text-right">
                                            <?php echo number_format($hours, 2); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format(($hours * $staffCost) / ($staffTaxRate + 1), 2); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format(($hours * ($staffCost + $staffProfit)) / ($staffTaxRate + 1), 2); ?>
                                        </td>
                                        <td class="text-right">
                                            <?php echo number_format(($hours * $staffProfit) / ($staffTaxRate + 1), 2); ?>
                                        </td>
                                    </tr>
                                    <?php
                                }
                                ?>
                                <tr>
                                    <td colspan="4"></td>
                                </tr>
                                <tr>
                                    <th>Total</th>
                                    <th class="text-right">
                                        <?php echo number_format($totals['time']['total']['project'][$pid]['total'], 2); ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo '$' . number_format($totals['cost']['total']['project'][$pid]['total'], 2); ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo '$' . number_format($totals['cost']['total']['project'][$pid]['total'] + $totals['profit']['total']['project'][$pid]['total'], 2); ?>
                                    </th>
                                    <th class="text-right">
                                        <?php echo '$' . number_format($totals['profit']['total']['project'][$pid]['total'], 2); ?>
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
    </div>


    <div class="container">
        <?php
        $active = 'active';
        foreach ($totals['time'] as $date => $daily) {
            if ($date == 'total') continue;
            ?>
            <div id="daily-<?php echo $date; ?>" class="tab-pane <?php echo $active; ?>">
                <h3>
                    <?php
                    echo date('D, j-M', strtotime($date));
                    echo ' - ';
                    echo number_format($daily['total'], 2);
                    echo 'h = $';
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
                                    <?= $sid ?>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-striped table-condensed">
                                        <tr>
                                            <th width="55%">profile</th>
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
                                            ?>
                                            <tr>
                                                <td><?php echo $pid; ?></td>
                                                <td class="text-right">
                                                    <?php echo number_format($hours, 2); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo number_format(($hours * $timeSheet->getStaffCost($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo number_format(($hours * ($timeSheet->getStaffCost($sid, $pid) + $timeSheet->getStaffProfit($sid, $pid))) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo number_format(($hours * $timeSheet->getStaffProfit($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-right">
                                                <?php echo number_format($daily['staff'][$sid]['total'], 2); ?>
                                            </th>
                                            <th class="text-right">
                                                <?php echo '$' . number_format($totals['cost'][$date]['staff'][$sid]['total'], 2); ?>
                                            </th>
                                            <th class="text-right">
                                                <?php echo '$' . number_format($totals['cost'][$date]['staff'][$sid]['total'] + $totals['profit'][$date]['staff'][$sid]['total'], 2); ?>
                                            </th>
                                            <th class="text-right">
                                                <?php echo '$' . number_format($totals['profit'][$date]['staff'][$sid]['total'], 2); ?>
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
                        <h4><i class="icon-briefcase"></i> Hours by Project</h4>
                        <?php
                        foreach ($daily['project'] as $pid => $staffs) {
                            if ($pid == 'total') continue;
                            ?>
                            <div class="row">
                                <div class="col-md-2">
                                    <?= $pid ?>
                                </div>
                                <div class="col-md-10">
                                    <table class="table table-striped table-condensed">
                                        <tr>
                                            <th width="50%">staff</th>
                                            <th width="25%">hours</th>
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
                                            ?>
                                            <tr>
                                                <td><?php echo $sid; ?></td>
                                                <td class="text-right">
                                                    <?php echo number_format($hours, 2); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo number_format(($hours * $timeSheet->getStaffCost($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo number_format(($hours * ($timeSheet->getStaffCost($sid, $pid) + $timeSheet->getStaffProfit($sid, $pid))) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php echo number_format(($hours * $timeSheet->getStaffProfit($sid, $pid)) / ($timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                                </td>
                                            </tr>
                                            <?php
                                        }
                                        ?>
                                        <tr>
                                            <th>Total</th>
                                            <th class="text-right">
                                                <?php echo number_format($daily['project'][$pid]['total'], 2); ?>
                                            </th>
                                            <th class="text-right">
                                                <?php echo '$' . number_format($totals['cost'][$date]['project'][$pid]['total'], 2); ?>
                                            </th>
                                            <th class="text-right">
                                                <?php echo '$' . number_format($totals['cost'][$date]['project'][$pid]['total'] + $totals['profit'][$date]['project'][$pid]['total'], 2); ?>
                                            </th>
                                            <th class="text-right">
                                                <?php echo '$' . number_format($totals['profit'][$date]['project'][$pid]['total'], 2); ?>
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
            </div>
            <?php
            $active = '';
        }
        ?>

    </div>


</div>