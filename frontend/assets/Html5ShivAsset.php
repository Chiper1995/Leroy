<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Для поддержки совместимости с IE8
 */
class Html5ShivAsset extends AssetBundle
{
    public $sourcePath = '@bower/html5shiv/dist/';
    public $css = [
    ];
    public $js = [
        'html5shiv.min.js',
    ];
    public $depends = [
    ];

    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position' => View::POS_HEAD
    ];

}
