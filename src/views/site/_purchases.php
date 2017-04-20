<?php

/**
 * @var View $this
 * @var array $times
 */

use yii\web\View;

if (!$times) {
    return;
}
?>

<h2><?= Yii::t('app', 'Purchases') ?></h2>

<ul id="purchases-tab" class="nav nav-pills" role="tablist">
    <?php
    $active = 'active';
    foreach ($times as $sid => $_times) {
        ?>
        <li class="<?= $active ?>"><a href="#purchase-<?= $sid ?>"><?= Yii::$app->timeSheet->staff[$sid]['name'] ?></a></li>
        <?php
        $active = '';
    }
    ?>
</ul>
<div class="tab-content">
    <?php
    $active = 'active';
    foreach ($times as $sid => $_times) {
        ?>
        <div role="tabpanel" class="tab-pane <?= $active ?>" id="purchase-<?= $sid ?>">
            <?= $this->render('_purchase', ['sid' => $sid, 'times' => $_times]) ?>
        </div>
        <?php
        $active = '';
    }
    ?>
</div>
