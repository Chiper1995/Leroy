<?php
/**
 * Created by PhpStorm.
 * User: Artem
 * Date: 19.08.2018
 * Time: 3:19
 */

namespace frontend\assets;

use yii\web\AssetBundle;

class JournalAutoSaveFormAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
    ];
    public $js = [
        'js/journal-auto-save-form.min.js',
    ];
    public $depends = [
        'yii\web\JqueryAsset',
    ];
}