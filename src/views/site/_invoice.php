<?php

/**
 * @var View $this
 * @var string $pid
 * @var array $times
 */

use yii\web\View;

/** @var \app\components\TimeSheet $timeSheet */
$timeSheet = Yii::$app->timeSheet;
$project = $timeSheet->projects[$pid];
?>


<h3><?php echo $pid; ?></h3>

<table class="table">
    <tr>
        <td width="15%"><?= Yii::t('app', 'To Email Address') ?></td>
        <td width="85%"><?php echo $project['email']; ?></td>
    </tr>
    <tr>
        <td><?= Yii::t('app', 'Saasu Contact UID') ?></td>
        <td><?php echo $project['saasu_contact_uid']; ?></td>
    </tr>
    <tr>
        <td>Items</td>
        <td>
            <table class="table table-condensed">
                <tr>
                    <th width="70%"><?= Yii::t('app', 'description') ?></th>
                    <th width="10%"><?= Yii::t('app', 'quantity') ?></th>
                    <th width="10%"><?= Yii::t('app', 'amount') ?></th>
                    <th width="10%"><?= Yii::t('app', 'total') ?></th>
                    <th width="10%"><?= Yii::t('app', 'total&nbsp;ex') ?></th>
                </tr>
                <?php
                $total = array('quantity' => 0, 'amount' => 0);
                foreach ($times as $sid => $dates) {
                    foreach ($dates as $date => $tasks) {
                        foreach ($tasks as $task) {
                            $total['quantity'] += $task['hours'];
                            $total['amount'] += $task['hours'] * $task['rate'];
                            ?>
                            <tr>
                                <td><?= $date . ' ' . $sid . ': ' . str_replace("\n", '<br/>', $task['description']) ?></td>
                                <td class="text-right">
                                    <?php echo number_format($task['hours'], 2); ?>
                                </td>
                                <td class="text-right"><?php echo $task['rate']; ?>
                                </td>
                                <td class="text-right">
                                    <?php echo '$' . number_format($task['hours'] * $task['rate'], 2); ?>
                                </td>
                                <td class="text-right">
                                    <?php echo '$' . number_format($task['hours'] * $task['rate'] / ($timeSheet->getProjectTaxRate($pid) + 1), 2); ?>
                                </td>
                            </tr>
                            <?php
                        }
                    }
                }
                ?>
                <tr>
                    <td><?= Yii::t('app', 'Total') ?></td>
                    <td class="text-right">
                        <?php echo number_format($total['quantity'], 2); ?>
                    </td>
                    <td>
                    </td>
                    <td class="text-right">
                        <?php echo '$' . number_format($total['amount'], 2); ?>
                    </td>
                    <td class="text-right">
                        <?php echo '$' . number_format($total['amount'] / ($timeSheet->getProjectTaxRate($pid) + 1), 2); ?>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
    <tr>
        <td><?= Yii::t('app', 'Times') ?></td>
        <td>
            <pre><?= $this->render('_invoice_times', ['times' => $times]); ?></pre>
        </td>
    </tr>
</table>
