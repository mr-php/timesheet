<?php

use yii\helpers\Json;

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

// Define application aliases
Yii::setAlias('@root', dirname(dirname(__DIR__)));
Yii::setAlias('@app', dirname(__DIR__));
Yii::setAlias('@runtime', '@root/runtime');
Yii::setAlias('@web', '@root/web');
Yii::setAlias('@webroot', dirname(__DIR__) . '/web');

// Load $merge configuration files
$applicationType = php_sapi_name() == 'cli' ? 'console' : 'web';
$env = YII_ENV;
$configDir = __DIR__;

return \yii\helpers\ArrayHelper::merge(
    require("{$configDir}/common.php"),
    require("{$configDir}/{$applicationType}.php"),
    (file_exists("{$configDir}/common-{$env}.php")) ? require("{$configDir}/common-{$env}.php") : [],
    (file_exists("{$configDir}/{$applicationType}-{$env}.php")) ? require("{$configDir}/{$applicationType}-{$env}.php") : [],
    (file_exists(getenv('APP_CONFIG_FILE'))) ? require(getenv('APP_CONFIG_FILE')) : [],
    getenv('APP_CONFIG_JSON_BASE64') ? Json::decode(base64_decode(getenv('APP_CONFIG_JSON_BASE64'))) : []
);
