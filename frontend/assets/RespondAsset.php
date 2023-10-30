<?php

namespace frontend\assets;

use yii\web\AssetBundle;
use yii\web\View;

/**
 * Для поддержки совместимости с IE8
 */
class RespondAsset extends AssetBundle
{
    public $sourcePath = '@bower/respond/dest/';
    public $css = [
    ];
    public $js = [
        'respond.min.js',
    ];
    public $depends = [
    ];

    public $jsOptions = [
        'condition' => 'lt IE 9',
        'position' => View::POS_HEAD
    ];
}
