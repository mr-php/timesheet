<?php

/**
 * @var View $this
 * @var array $times
 */

use yii\web\View;

/** @var \app\components\TimeSheet $timeSheet */
$timeSheet = Yii::$app->timeSheet;

if (!$times) {
    return;
}
?>

<h2><?= Yii::t('app', 'Invoices') ?></h2>

<ul id="invoices-tab" class="nav nav-pills" role="tablist">
    <?php
    $active = 'active';
    foreach ($times as $pid => $invoice) {
        ?>
        <li class="<?= $active ?>"><a href="#invoice-<?= $pid ?>"><?= $pid ?></a></li>
        <?php
        $active = '';
    }
    ?>
</ul>
<div class="tab-content">
    <?php
    $active = 'active';
    foreach ($times as $pid => $_times) {
        ?>
        <div role="tabpanel" class="tab-pane <?= $active ?>" id="invoice-<?= $pid ?>">
            <?= $this->render('_invoice', ['pid' => $pid, 'times' => $_times]) ?>
        </div>
        <?php
        $active = '';
    }
    ?>
</div>
