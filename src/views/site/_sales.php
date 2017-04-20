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

<h2><?= Yii::t('app', 'Sales') ?></h2>

<ul id="sales-tab" class="nav nav-pills" role="tablist">
    <?php
    $active = 'active';
    foreach ($times as $pid => $_times) {
        ?>
        <li class="<?= $active ?>"><a href="#sales-<?= $pid ?>"><?= Yii::$app->timeSheet->projects[$pid]['name'] ?></a></li>
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
        <div role="tabpanel" class="tab-pane <?= $active ?>" id="sales-<?= $pid ?>">
            <?= $this->render('_sale', ['pid' => $pid, 'times' => $_times]) ?>
        </div>
        <?php
        $active = '';
    }
    ?>
</div>
