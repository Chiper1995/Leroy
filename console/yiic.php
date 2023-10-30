<?php

$_SERVER['SERVER_ADDR'] = 'localhost';

/* setup default time zone */
//date_default_timezone_set('UTC');
date_default_timezone_set('Europe/Moscow');
// определяем локальный ли сервер
define('IS_DEV_SERVER', true);

// Окружение
define('YII_ENV', IS_DEV_SERVER ? 'dev' : 'prod');

if (IS_DEV_SERVER) {
    // Включаем вывод все ошибок
    error_reporting(E_ALL);
    ini_set('display_errors', true);

    // Для конфигурации приложения Yii
    defined('YII_DEBUG') or define('YII_DEBUG', true);
}

defined('YII_APP_BASE_PATH') or define('YII_APP_BASE_PATH', dirname(__DIR__));

require_once(YII_APP_BASE_PATH . '/vendor/autoload.php');
require_once(YII_APP_BASE_PATH . '/vendor/yiisoft/yii2/Yii.php');
require_once(YII_APP_BASE_PATH . '/common/config/bootstrap.php');

$config = require(YII_APP_BASE_PATH . '/console/config/main.php');

$application = new yii\console\Application($config);
$exitCode = $application->run();
exit($exitCode);
