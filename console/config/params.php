<?php

$consolePath = dirname(__DIR__);

// Параметры окружения
$paramsEnvFile = $consolePath . '/config/environments/params-' . YII_ENV . '.php';
$paramsEnvFileArray = file_exists($paramsEnvFile) ? require($paramsEnvFile) : array();

// Общие параметры
$paramsCommonFile = $consolePath . '/../common/config/params.php';
$paramsCommonArray = file_exists($paramsCommonFile) ? require($paramsCommonFile) : array();

return yii\helpers\ArrayHelper::merge(
    $paramsCommonArray,
    yii\helpers\ArrayHelper::merge(
        [

        ],
        $paramsEnvFileArray
    )
);