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
            'label' => '<span class="glyphicon glyphicon-cloud-download"></span>&nbsp;' . Yii::t('app', 'Toggl'),
            'encode' => false,
            'url' => ['/site/import-toggl'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
        [
            'label' => '<span class="glyphicon glyphicon-cloud-download"></span>&nbsp;' . Yii::t('app', 'Upwork'),
            'encode' => false,
            'url' => ['/site/import-upwork'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
        [
            'label' => '<span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;' . Yii::t('app', 'Saasu'),
            'encode' => false,
            'url' => ['/site/export-saasu'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
        [
            'label' => '<span class="glyphicon glyphicon-cloud-upload"></span>&nbsp;' . Yii::t('app', 'ZipBooks'),
            'encode' => false,
            'url' => ['/site/export-zipbooks'],
            'linkOptions' => [
                'data-confirm' => Yii::t('app', 'Are you sure?'),
            ],
        ],
        [
            'label' => '<span class="glyphicon glyphicon-oil"></span>&nbsp;' . Yii::t('app', 'Dump'),
            'encode' => false,
            'url' => ['/site/dump'],
        ],
    ],
    'options' => ['class' => 'navbar-nav'],
]);
echo Nav::widget([
    'options' => ['class' => 'navbar-nav navbar-right'],
    'dropDownCaret' => false,
    'items' => [
        [
            'label' => '<span class="glyphicon glyphicon-wrench"></span>&nbsp;', // . Yii::t('app', 'Settings'),
            'encode' => false,
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
                    'label' => 'Upwork Settings',
                    'url' => ['/site/upwork-settings'],
                ],
                [
                    'label' => 'Saasu Settings',
                    'url' => ['/site/saasu-settings'],
                ],
                [
                    'label' => 'ZipBooks Settings',
                    'url' => ['/site/zipbooks-settings'],
                ],
            ],
        ],
        [
            'label' => '<span class="glyphicon glyphicon-link"></span>&nbsp;', // . Yii::t('app', 'Links'),
            'encode' => false,
            'items' => [
                [
                    'label' => 'Toggl',
                    'url' => 'https://toggl.com/app',
                    'linkOptions' => [
                        'target' => '_blank',
                    ],
                ],
                [
                    'label' => 'Upwork',
                    'url' => 'https://upwork.com/',
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
                    'label' => 'ZipBooks',
                    'url' => 'https://app.zipbooks.com/',
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
                [
                    'label' => Yii::t('app', 'KickAss!'),
                    'url' => "javascript:var KICKASSVERSION='2.0';var s = document.createElement('script');s.type='text/javascript';document.body.appendChild(s);s.src='//hi.kickassapp.com/kickass.js';void(0);",
                ],
            ],
        ],
    ],

]);
NavBar::end();