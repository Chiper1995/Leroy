<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 09.07.2018
 * Time: 23:58
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class ModalPjaxAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/modal-pjax.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}