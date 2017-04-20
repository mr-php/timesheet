<?php

use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\helpers\Url;

/**
 * @var $this \yii\web\View
 */

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
        [
            'label' => Yii::t('app', 'Dump'),
            'url' => ['/site/dump'],
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
                    'label' => 'Toggl Settings',
                    'url' => ['/site/toggl-settings'],
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