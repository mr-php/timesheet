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
    'id' => 'app',
    'language' => 'en',
    'basePath' => dirname(__DIR__),
    'vendorPath' => '@app/../vendor',
    'runtimePath' => '@app/../runtime',
    // Bootstrapped modules are loaded in every request
    'bootstrap' => [
        'log',
    ],
    'components' => [
        'timeSheet' => [
            'class' => 'app\components\TimeSheet',
            'staff' => [
                'brett' => [
                    'name' => 'Brett',
                    'toggl_api_key' => '817212b50437a8531fdac89bc22e7dc8',
                    'rate' => 100,
                    'cost' => 70,
                    'multiplier' => 1,
                    'tax_rate' => 0.1,
                    'projects' => [
                        'test' => [
                            'rate' => 200,
                            'multiplier' => 0.75,
                        ],
                    ],
                ],
            ],
            'projects' => [
                'test' => [
                    'name' => 'Test',
                    'email' => 'test@mailinator.com',
                    'saasu_contact_uid' => '123',
                    'base_rate' => 150,
                    'base_hours' => 2,
                    'cap_hours' => 4,
                ],
            ],
        ],
    ],
];
