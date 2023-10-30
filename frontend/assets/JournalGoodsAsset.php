<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 19.08.2018
 * Time: 3:14
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class JournalGoodsAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/journal-goods.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}