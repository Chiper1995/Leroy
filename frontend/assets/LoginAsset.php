<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/login.min.css',
    ];
    public $js = [
        'js/login.min.js',
    ];
    public $depends = [
        'frontend\assets\IE8CompatibilityAsset',
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    public $jsOptions = [
    ];
}
