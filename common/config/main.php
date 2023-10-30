<?php

$commonConfigPath = dirname(__FILE__);

$params = require($commonConfigPath . '/params.php');
$mainEnvFile = $commonConfigPath . '/environments/main-' . YII_ENV . '.php';
$mainEnvConfiguration = file_exists($mainEnvFile) ? require($mainEnvFile) : [];

$config = yii\helpers\ArrayHelper::merge(
    [
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => $params['db.connectionString'],
                'username' => $params['db.username'],
                'password' => $params['db.password'],
                'enableSchemaCache' => true,
                'schemaCacheDuration' => YII_DEBUG ? 0 : 86400000,
                'charset' => 'utf8mb4',
                'tablePrefix' => $params['db.tablePrefix'],
            ],
            'mailer' => [
                'class' => 'yii\swiftmailer\Mailer',
                'useFileTransport' => $params['mailer.useFileTransport'],
                'messageConfig' => [
                    'from' => ['no-reply-leroy-merlin@mail.ru'=>'Семьи Леруа Мерлен'],
                ],
            ],
        ],
        'params' => $params,
    ],
    $mainEnvConfiguration
);

return $config;
