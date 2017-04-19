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
    <?php $this->head() ?>
    <style>
        html {
            position: relative;
            min-height: 100%;
        }

        body {
            padding-top: 60px;
            margin-bottom: 50px;
        }

        .footer {
            position: absolute;
            bottom: 0;
            width: 100%;
            height: 45px;
            background-color: #f8f8f8;
            border-top: 1px solid #e7e7e7;
            padding-top: 10px;
        }
    </style>
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
            'label' => Yii::t('app', 'Import Toggl'),
            'url' => ['/site/import-toggl'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
        [
            'label' => Yii::t('app', 'Export Saasu'),
            'url' => ['/site/export-saasu'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
    ],
    'options' => ['class' => 'navbar-nav'],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'items' => [
        [
            'label' => Yii::t('app', 'Settings'),
            'items' => [
                [
                    'label' => 'TimeSheet Settings',
                    'url' => ['/site/timesheet-settings'],
                ],
                [
                    'label' => 'Saasu Settings',
                    'url' => ['/site/saasu-settings'],
                ],
            ],
        ],
        [
            'label' => Yii::t('app', 'Links'),
            'items' => [
                [
                    'label' => 'Toggl',
                    'url' => 'https://toggl.com/app',
                    'linkOptions' => [
                        'target' => '_blank',
                    ],
                ],
                [
                    'label' => 'Saasu',
                    'url' => 'https://secure.saasu.com/',
                    'linkOptions' => [
                        'target' => '_blank',
                    ],
                ],
                [
                    'label' => 'GitHub',
                    'url' => 'https://github.com/mr-php/timesheet',
                    'linkOptions' => [
                        'target' => '_blank',
                    ],
                ],
                [
                    'label' => 'Heroku',
                    'url' => 'https://dashboard.heroku.com/apps/mrphp-timesheet/settings',
                    'linkOptions' => [
                        'target' => '_blank',
                    ],
                ],
            ],
        ],
    ],

]);
NavBar::end();
?>

<div class="container">
    <?= $content ?>
</div>

<footer class="footer">
    <div class="container">
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
