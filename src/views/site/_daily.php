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

<h2><?= Yii::t('app', 'Daily') ?></h2>

<ul id="daily-tab" class="nav nav-pills" role="tablist">
    <?php
    $active = 'active';
    foreach ($totals['time'] as $date => $daily) {
        if ($date == 'total') continue;
        ?>
        <li class="<?= $active ?>"><a href="#day-<?= $date ?>"><?= date('D, j-M', strtotime($date)) ?></a></li>
        <?php
        $active = '';
    }
    ?>
</ul>
<div class="tab-content">
    <?php
    $active = 'active';
    foreach ($totals['time'] as $date => $daily) {
        if ($date == 'total') continue;
        ?>
        <div role="tabpanel" class="tab-pane <?= $active ?>" id="day-<?= $date ?>">
            <?= $this->render('_day', ['totals' => $totals, 'daily' => $daily, 'date' => $date]) ?>
        </div>
        <?php
        $active = '';
    }
    ?>
</div>
