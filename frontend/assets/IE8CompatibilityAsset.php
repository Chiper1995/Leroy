<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Для поддержки совместимости с IE8
 */
class IE8CompatibilityAsset extends AssetBundle
{
    public $css = [
    ];
    public $js = [
        'js/main.min.js'
    ];
    public $depends = [
        'frontend\assets\Html5ShivAsset',
        'frontend\assets\RespondAsset',
    ];
    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position' => View::POS_HEAD
    ];

}
