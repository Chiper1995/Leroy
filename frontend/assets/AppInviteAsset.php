<?php
namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class AppInviteAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'css/app-invite.min.css',
    ];

    public $js = [
        'js/app-invite.min.js',
    ];

    public $depends = [
        'frontend\assets\IE8CompatibilityAsset',
    ];
}