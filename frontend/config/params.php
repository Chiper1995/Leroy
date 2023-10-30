<?php

$frontendPath = dirname(__DIR__);

// Параметры окружения
$paramsEnvFile = $frontendPath . '/config/environments/params-' . YII_ENV . '.php';
$paramsEnvFileArray = file_exists($paramsEnvFile) ? require($paramsEnvFile) : array();

// Общие параметры
$paramsCommonFile = $frontendPath . '/../common/config/params.php';
$paramsCommonArray = file_exists($paramsCommonFile) ? require($paramsCommonFile) : array();

return yii\helpers\ArrayHelper::merge(
    $paramsCommonArray,
    yii\helpers\ArrayHelper::merge(
        [
            'url.rules' => [
                '/' => 'journal/index',
                ['class' => \frontend\urlRules\AllJournalUrlRule::className(),],
                ['class' => \frontend\urlRules\MyTasksUrlRule::className(),],
                ['class' => \frontend\urlRules\MyJournalUrlRule::className(),],
                '<controller:(registration)>/complete' => '<controller>/complete',
                '<controller:(registration)>/start' => '<controller>/step',
                '<controller:(registration)>/<step>' => '<controller>/step',
                '<controller:(registration)>' => '<controller>/index',
                '<action:(signup|login|request-password-reset|reset-password)>' => 'site/<action>',
                '/help/<controller:(presentation)>/<action:(view|create|update|delete)>' => '<controller>/<action>',
                '/help/<controller:(presentation)/>' => '<controller>/',
                '/help/<controller:(presentation)>' => '<controller>/',
            ],

            // Показывать уведомление о смене адреса проекта
            'showChangeAddressInform' => false,
        ],
        $paramsEnvFileArray
    )
);