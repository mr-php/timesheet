<?php

$url = parse_url(getenv('DATABASE_URL'));
if (getenv('HEROKU')) {
    return [
        'class' => 'yii\db\Connection',
        'dsn' => 'pgsql:host=' . $url['host'] . ';dbname=' . substr($url['path'], 1),
        'username' => $url['user'],
        'password' => $url['pass'],
        'charset' => 'utf8',
    ];
}
return [
    'class' => 'yii\db\Connection',
    'dsn' => 'mysql:host=' . $url['host'] . ';dbname=' . substr($url['path'], 1),
    'username' => $url['user'],
    'password' => $url['pass'],
    'charset' => 'utf8',
];
