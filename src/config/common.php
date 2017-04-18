<?php

// Basic configuration, used in web and console applications

date_default_timezone_set(getenv('APP_TIMEZONE'));

return [
    'id' => getenv('APP_NAME'),
    'name' => getenv('APP_TITLE'),
    'language' => 'en',
    'basePath' => dirname(__DIR__),
    'vendorPath' => '@app/../vendor',
    'runtimePath' => '@app/../runtime',
    'bootstrap' => [
        'log',
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'timeSheet' => [
            'class' => 'app\components\TimeSheet',
        ],
    ],
];
