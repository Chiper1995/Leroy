<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.07.2018
 * Time: 23:48
 */

namespace frontend\widgets\GiveGiftLink;

use yii\web\AssetBundle;

class GiveGiftLinkAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/give-gift-link.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}