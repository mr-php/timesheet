<?php

// Basic configuration, used in web and console applications

date_default_timezone_set(getenv('APP_TIMEZONE'));

function debug($var, $name = null, $attributesOnly = true)
{
    $bt = debug_backtrace();
    $file = str_ireplace(dirname(dirname(__FILE__)), '', $bt[0]['file']);
    if (!class_exists('\yii\db\BaseActiveRecord', false))
        $attributesOnly = false;
    $name = $name ? '<b><span style="font-size:18px;">' . $name . ($attributesOnly ? ' [attributes]' : '') . '</span></b>:<br/>' : '';
    echo '<div style="background: #FFFBD6">';
    echo '<span style="font-size:12px;">' . $name . ' ' . $file . ' on line ' . $bt[0]['line'] . '</span>';
    echo '<div style="border:1px solid #000;">';
    echo '<pre>';
    if (is_scalar($var)) {
        var_dump($var);
    } elseif ($attributesOnly && $var instanceof \yii\db\BaseActiveRecord) {
        print_r($var->attributes);
    } elseif ($attributesOnly && is_array($var) && current($var) instanceof \yii\db\BaseActiveRecord) {
        foreach ($var as $k => $_var) {
            $var[$k] = $_var->attributes;
        }
        print_r($var);
    } else {
        print_r($var);
    }
    echo '</pre></div></div>';
}

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
        'user' => [
            'class' => 'yii\web\User',
            'identityClass'=>'app\components\NullUser',
        ],
        'saasu' => [
            'class' => 'app\components\Saasu',
        ],
        'timeSheet' => [
            'class' => 'app\components\TimeSheet',
        ],
        'toggl' => [
            'class' => 'app\components\Toggl',
        ],
    ],
];
