<?php

// Basic configuration, used in web and console applications
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
        'db' => require(__DIR__ . '/db.php'),
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'class' => 'yii\web\User',
            'identityClass' => 'app\components\NullUser',
        ],
        'saasu' => [
            'class' => 'app\components\Saasu',
        ],
        'settings' => [
            'class' => 'yii2mod\settings\components\Settings',
        ],
        'timeSheet' => [
            'class' => 'app\components\TimeSheet',
        ],
        'toggl' => [
            'class' => 'app\components\Toggl',
        ],
        'zipBooks' => [
            'class' => 'app\components\ZipBooks',
        ],
    ],
];
