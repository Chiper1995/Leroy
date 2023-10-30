<?php

$commonConfigPath = dirname(__FILE__);

// if exists, include it, otherwise set as an empty array
$commonEnvParamsFile = $commonConfigPath . '/environments/params-' . YII_ENV . '.php';
$commonEnvParams = file_exists($commonEnvParamsFile) ? require($commonEnvParamsFile) : [];

return yii\helpers\ArrayHelper::merge(
    [
        'version' => '16.01.20',
        'startUrl' => 'http://families.lm.rockage.ru/',

        'user.passwordResetTokenExpire' => 60 * 60, //час
    ],
    $commonEnvParams
);