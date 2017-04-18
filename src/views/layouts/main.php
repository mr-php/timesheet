<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Html;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 * @var $content string
 */

// Register asset bundles
\yii\web\YiiAsset::register($this);
\yii\bootstrap\BootstrapAsset::register($this);

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <link rel="shortcut icon" href="<?= Yii::getAlias('@web'); ?>/favicon.png" type="image/png">
    <?php $this->registerCss('body{padding-top: 60px;}'); ?>
    <?php $this->head() ?>
</head>
<body>
<?php $this->beginBody() ?>

<?php
NavBar::begin([
    'brandLabel' => Yii::$app->name,
    'brandUrl' => Url::home(),
    'options' => ['class' => 'navbar-default navbar-fixed-top navbar'],
    'innerContainerOptions' => ['class' => 'container'],
]);
echo Nav::widget([
    'items' => [
        [
            'label' => Yii::t('app', 'Toggl Import'),
            'url' => ['/site/import-toggl'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
        [
            'label' => Yii::t('app', 'Saasu Export'),
            'url' => ['/site/export-saasu'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
    ],
    'options' => ['class' => 'navbar-nav'],
]);
NavBar::end();
?>

<div class="wrap">
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
        <hr/>
        <p class="pull-left">
            <span class="label label-primary"><?= Yii::$app->id; ?></span>
        </p>
        <p class="pull-right">
            <span class="label label-default"><?= getenv('HOSTNAME') ?></span>
            <span class="label <?= YII_ENV_PROD ? 'label-success' : 'label-danger' ?>"><?= YII_ENV ?></span>
            <span class="label label-warning <?= YII_DEBUG ? '' : 'hidden' ?>">debug</span>
        </p>
    </div>
</footer>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
