<?php

/**
 * @var View $this
 * @var string $sid
 * @var array $times
 */

use app\components\Helper;
use yii\web\View;

$staff = Yii::$app->timeSheet->staff[$sid];
?>


<h3><?= $staff['name'] ?></h3>

<table class="table">
    <tr>
        <td width="15%"><?= Yii::t('app', 'Email') ?></td>
        <td width="85%"><?php echo $staff['email']; ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app', 'Xero Contact UID') ?></td>
        <td><?= $staff['xero_contact_id'] ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app', 'Xero Tax Code') ?></td>
        <td><?= $staff['xero_tax_code'] ?></td>
    </tr>
    <tr>
        <td>Items</td>
        <td>
            <table class="table table-condensed">
                <tr>
                    <th width="70%"><?= Yii::t('app', 'description') ?></th>
                    <th width="70%"><?= Yii::t('app', 'xero account code') ?></th>
                    <th width="70%"><?= Yii::t('app', 'xero item code') ?></th>
                    <th width="10%"><?= Yii::t('app', 'quantity') ?></th>
                    <th width="10%"><?= Yii::t('app', 'amount') ?></th>
                    <th width="10%"><?= Yii::t('app', 'total') ?></th>
                    <th width="10%"><?= Yii::t('app', 'total&nbsp;ex') ?></th>
                </tr>
                <?php
                $total = array('quantity' => 0, 'amount' => 0);
                foreach ($times as $pid => $dates) {
                    foreach ($dates as $date => $tasks) {
                        foreach ($tasks as $task) {
                            $total['quantity'] += round($task['hours'], 2);
                            $total['amount'] += round($task['hours'], 2) * $task['cost'];
                            ?>
                            <tr>
                                <td>
                                    <?= $date . ' ' . Yii::$app->timeSheet->projects[$task['pid']]['name'] . ' ' . Helper::formatHours(round($task['hours'],2)) . ' - ' . str_replace("\n", '<br/>', $task['description']) ?>
                                </td>
                                <td>
                                    <?= Yii::$app->timeSheet->staff[$task['sid']]['xero_sale_account_id'] ?>
                                </td>
                                <td>
                                    <?= Yii::$app->timeSheet->staff[$task['sid']]['xero_item_code'] ?>
                                </td>
                                <td class="text-right">
                                    <?php echo number_format($task['hours'], 2); ?>
                                </td>
                                <td class="text-right"><?php echo $task['cost']; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo '$' . number_format($task['hours'] * $task['cost'], 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo '$' . number_format($task['hours'] * $task['cost'] / (Yii::$app->timeSheet->getStaffTaxRate($sid, $pid) + 1), 2); ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <tr>
                    <td><?= Yii::t('app', 'Total') ?></td>
                    <td>
                    </td>
                    <td>
                    </td>
                    <td class="text-right">
                        <?php echo number_format($total['quantity'], 2); ?>
                    </td>
                    <td>
                    </td>
                    <td class="text-right">
                        <?php echo '$' . number_format($total['amount'], 2); ?>
                    </td>
                    <td class="text-right">
                        <?php echo '$' . number_format($total['amount'] / (Yii::$app->timeSheet->getStaffTaxRate($sid) + 1), 2); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><?= Yii::t('app', 'Times') ?></td>
        <td>
            <pre><?= $this->render('_purchase-times', ['times' => $times]); ?></pre>
        </td>
    </tr>
</table>
