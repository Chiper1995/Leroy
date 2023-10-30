<?php

set_time_limit(3000);
ini_set('max_execution_time', 3000);

$_SERVER['SCRIPT_NAME'] = '/index.php';

// По порту определяем локальный ли сервер
define('IS_DEV_SERVER', $_SERVER['SERVER_PORT'] == 8080);

// Окружение
define('YII_ENV', IS_DEV_SERVER ? 'dev' : 'prod');

if (IS_DEV_SERVER) {
    // Включаем вывод все ошибок
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    // Для конфигурации приложения Yii
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(dirname(__DIR__)));

require_once(YII_APP_BASE_PATH . '/vendor/autoload.php');
require_once(YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');
require_once(YII_APP_BASE_PATH . '/common/config/bootstrap.php');



define('YII_DEBUG', true);

$config = require(YII_APP_BASE_PATH . '/frontend/config/main.php');
//echo '<pre>'.\yii\helpers\VarDumper::dumpAsString($config, 10, true).'</pre>'; die();

$application = new yii\web\Application($config);
$application->run();
