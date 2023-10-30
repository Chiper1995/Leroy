<?php
/*
Yii command:
yii asset tools/gulp/assets-config.php config/assets-prod.php
*/

Yii::setAlias('@webroot', str_replace('\\', '/', dirname(__FILE__)) . '/../../frontend/web');
Yii::setAlias('@web', '/');

return [
    'jsCompressor' => 'gulp compress-js --gulpfile tools/gulp/gulpfile.js --src {from} --dist {to}',
    'cssCompressor' => 'gulp compress-css --gulpfile tools/gulp/gulpfile.js --src {from} --dist {to}',
    'bundles' => [
        'frontend\assets\AppAsset',
    ],
    'targets' => [
        'all' => [
            'class' => 'yii\web\AssetBundle',
            'basePath' => '@webroot/assets',
            'baseUrl' => '@web/assets',
            'js' => 'js/all-{hash}.js',
            'css' => 'css/all-{hash}.css',
            'depends' => [
            ],
        ],
    ],
    // Asset manager configuration:
    'assetManager' => [
        'basePath' => '@webroot/assets',
        'baseUrl' => '@web/assets',
    ],
];
