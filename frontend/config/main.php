<?php
use kartik\datecontrol\Module;

$frontendPath = Yii::getAlias('@frontend');

$params = require($frontendPath . '/config/params.php');
$mainEnvFile = $frontendPath . '/config/environments/main-' . YII_ENV . '.php';
$mainEnvConfiguration = file_exists($mainEnvFile) ? require($mainEnvFile) : [];

$events = require($frontendPath . '/config/events.php');

//$assets = require($frontendPath . '/config/assets-prod.php' );

$config = yii\helpers\ArrayHelper::merge(
    [
        'id' => 'blacksense',
        'language' => 'ru',
        'sourceLanguage' => 'ru',
        'defaultRoute'=>'journal/index',
        'basePath' => $frontendPath,
        'bootstrap' => ['log'],
        'controllerNamespace' => 'frontend\controllers',
        'vendorPath' => '@vendor',
        'aliases' => [
            '@bower' => '@vendor/bower-asset',
            '@npm' => '@vendor/npm-asset',
        ],
        'components' => [
            'request' => [
                // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
                'cookieValidationKey' => 'sdfsshjkdghskhgslkghurtuhhjkbvxbJHJKGJhfksdjfgdkjgdfsdf',
                'parsers' => [
                    'application/json' => 'yii\web\JsonParser',
                ]
            ],
            'cache' => $params['cache'],
            'user' => [
                'class' => 'common\components\User',
                'identityClass' => 'common\models\User',
                'enableAutoLogin' => true,
            ],
            'authManager' => [
                'class' => 'common\rbac\PhpManager',
                'defaultRoles' => array('guest'),
                'itemFile' => '@common/config/auth.php',
            ],
            'errorHandler' => [
                'errorAction' => 'site/error',
            ],
            'urlManager' => [
                'enablePrettyUrl' => true,
                'showScriptName' => false,
                'enableStrictParsing' => false,
                'rules' => $params['url.rules']
            ],
            'assetManager' => [
                'appendTimestamp' => true,
                'linkAssets' => true,
                //'bundles' => $assets,
                'bundles' => [
                    \yii\web\JqueryAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \yii\jui\JuiAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                        'css' => ['css/lib.min.css'],
                    ],
                    \yii\web\YiiAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \yii\validators\ValidationAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \yii\widgets\ActiveFormAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \yii\widgets\PjaxAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \yii\grid\GridViewAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \yii\bootstrap\BootstrapAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'css' => ['css/lib.min.css'],
                    ],
                    \yii\bootstrap\BootstrapPluginAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    xj\bootbox\BootboxAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \kop\y2sp\assets\InfiniteAjaxScrollAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                    \execut\widget\TreeViewBowerAsset::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                        'css' => ['css/lib.min.css'],
                    ],
                    \webtoucher\cookie\AssetBundle::className() => [
                        'basePath' => '@frontend/web/',
                        'baseUrl' => '@web/',
                        'js' => ['js/lib.min.js'],
                    ],
                ],
            ],
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
			'sphinx' => [
				'class' => 'yii\sphinx\Connection',
				'dsn' => $params['sphinx.connectionString'],
				'username' => $params['sphinx.username'],
				'password' => $params['sphinx.password'],
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
            'formatter' => [
                'dateFormat' => 'php:d.m.Y',
                'datetimeFormat' => 'php:d.m.Y H:i:s',
                'timeFormat' => 'php:H:i:s',
                'decimalSeparator' => ',',
                'thousandSeparator' => ' ',
                'currencyCode' => '',
            ],
            'i18n' => [
                'messageFormatter' => [
                    'class'=>\common\components\MessageFormatter::className(),
                ]
            ],
            'html2pdf' => [
                'class' => 'yii2tech\html2pdf\Manager',
                'viewPath' => '@app/pdf',
                'converter' => [
                    'class' => 'yii2tech\html2pdf\converters\Mpdf',
                    'defaultOptions' => [
                        'pageSize' => 'A4'
                    ],
                ]
            ],
			's3bucket' => [
				'class' => \frostealth\yii2\aws\s3\Storage::className(),
				'region' => 'ru-central1',
				'credentials' => [ // Aws\Credentials\CredentialsInterface|array|callable
				   'key' => 'jZFW_8622P8pYxiTIYa8',
				   'secret' => 'uCLqQnsaNKVwvqPeWaGY4D26y5wXILZh6vFHqOtY',
				],
				'bucket' => 'family',
				'defaultAcl' => \frostealth\yii2\aws\s3\Storage::ACL_PUBLIC_READ,
				'debug' => false, // bool|array
				'options' => [
					'endpoint' => 'https://storage.yandexcloud.net'
				]
			],
        ],
        'modules' => [
            'datecontrol' =>  [
                'class' => '\kartik\datecontrol\Module',
                // format settings for displaying each date attribute (ICU format example)
                'displaySettings' => [
                    Module::FORMAT_DATE => 'dd.MM.yyyy',
                    Module::FORMAT_TIME => 'HH:mm:ss',
                    Module::FORMAT_DATETIME => 'dd.mm.yyyy HH:mm:ss',
                ],

                // format settings for saving each date attribute (PHP format example)
                'saveSettings' => [
                    Module::FORMAT_DATE => 'php:Y-m-d', // saves as unix timestamp
                    Module::FORMAT_TIME => 'php:H:i:s',
                    Module::FORMAT_DATETIME => 'php:Y-m-d H:i:s',
                ],

                'autoWidgetSettings' => [
                    Module::FORMAT_DATE => [
                        'type'=>\kartik\widgets\DatePicker::TYPE_COMPONENT_APPEND,
                        'removeButton' => false,
                        'pluginOptions'=>[
                            'autoclose'=>true,
                            'clearBtn'=>true,
                            'todayHighlight'=>true,
                        ]
                    ],
                    Module::FORMAT_DATETIME => [], // setup if needed
                    Module::FORMAT_TIME => [], // setup if needed
                ],

                'ajaxConversion' => false,
            ]
        ],
        'params' => $params,
    ],
    $events,
    $mainEnvConfiguration
);

// Преднастраиваем формы
\Yii::$container->set('yii\bootstrap\ActiveForm', [
    'enableAjaxValidation' => true,
    'validateOnSubmit'=>true,
    'validateOnChange'=>false,
    'validateOnBlur'=>false,
]);

\Yii::$container->set('yii\grid\GridView', [
    'layout'=>"{summary}\n<div class=\"grid-items-container\">{items}</div>\n{pager}",
]);

// Колонка с экшенами в гриде
\Yii::$container->set('yii\grid\ActionColumn', [
    'template'=>'{update}{delete}',
    'contentOptions' => [
        'class' => 'grid-button-cell'
    ],
    'buttonOptions' => [
        'data' => ['toggle' => 'tooltip', 'placement' => 'bottom',],
        'class' => 'grid-button'
    ],
]);

// Select2 widget
\Yii::$container->set('kartik\widgets\Select2', [
    'language' => 'ru',
    'theme' => kartik\widgets\Select2::THEME_BOOTSTRAP,
    'options' => ['placeholder'=>'',],
    'pluginOptions' => ['allowClear' => false,],
]);

\Yii::$container->set('yii\widgets\Pjax', [
    'timeout'=>5000
]);

//
if (YII_ENV != 'test') {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \common\components\DebugToolbarWithCheckRight::className(),
        'allowedIPs' => ['*'],
    ];
}

if ((YII_ENV_DEV)and(YII_ENV != 'test')) {
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
