<?php

/**
 * @link http://www.diemeisterei.de/
 *
 * @copyright Copyright (c) 2016 diemeisterei GmbH, Stuttgart
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

//date_default_timezone_set('Australia/Adelaide');

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
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'timeSheet' => [
            'class' => 'app\components\TimeSheet',
        ],
    ],
];
