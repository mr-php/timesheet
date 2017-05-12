<?php

return [
    'controllerNamespace' => 'app\commands',
    'params' => [
        'yii.migrations' => [
            //'@yii/rbac/migrations',
            //'@dektrium/user/migrations',
            //'@bedezign/yii2/audit/migrations',
            '@vendor/yii2mod/yii2-settings/migrations',
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => '\dmstr\console\controllers\MigrateController',
            'migrationPath' => null,
        ],
    ],
];
