<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 08.09.2018
 * Time: 15:58
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class MultiPhotoUploadAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/multi-photo-upload.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}