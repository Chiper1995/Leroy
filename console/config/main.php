<?php

$consolePath = Yii::getAlias('@console');

$params = require($consolePath . '/config/params.php');
$mainEnvFile = $consolePath . '/config/environments/main-' . YII_ENV . '.php';
$mainEnvConfiguration = file_exists($mainEnvFile) ? require($mainEnvFile) : [];

$events = require($consolePath . '/config/events.php');

return yii\helpers\ArrayHelper::merge(
    [
        'id' => 'app-console',
        'language' => 'ru',
        'basePath' => $consolePath,
        'vendorPath' => '@vendor',
        'bootstrap' => ['log'],
        'controllerNamespace' => 'console\controllers',
        'components' => [
            'db' => [
                'class' => 'yii\db\Connection',
                'dsn' => $params['db.connectionString'],
                'username' => $params['db.username'],
                'password' => $params['db.password'],
                'schemaCacheDuration' => YII_DEBUG ? 0 : 86400000,
                'charset' => 'utf8mb4',
                'tablePrefix' => $params['db.tablePrefix'],
            ],
            'cache' => $params['cache'],
            'authManager' => [
                'class' => 'common\rbac\PhpManager',
                'defaultRoles' => array('guest'),
                'itemFile' => '@common/config/auth.php',
            ],
            'mailer' => [
                'class' => $params['mailer.class'],
                // send all mails to a file by default. You have to set
                // 'useFileTransport' to false and configure a transport
                // for the mailer to send real emails.
                'useFileTransport' => $params['mailer.useFileTransport'],
                'transport' => [
                    'class' => $params['mailer.transport.class'],
                    'host' => $params['mailer.transport.host'],
                    'username' => $params['mailer.transport.username'],
                    'password' => $params['mailer.transport.password'],
                    'port' => $params['mailer.transport.port'],
                    'encryption' => $params['mailer.transport.encryption'],
                ],
                'messageConfig' => [
                    'from' => $params['mailer.messageConfig.from'],
                ],
            ],
            'user' => [
                'class' => 'yii\web\User',
                'identityClass' => 'app\models\User',
                'enableSession' => false,
            ],
        ],
        'params' => $params,
    ],
    $events
);
