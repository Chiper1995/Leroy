<?php

namespace common\widgets\EFineUploader;

use yii\web\AssetBundle;

class EFineUploaderAsset extends AssetBundle
{
    const version = '3.4.1';

    public $depends = [
        'yii\web\YiiAsset',
        'yii\web\JqueryAsset'
    ];

    public $js = [
        'jquery.fineuploader-{version}.js'
    ];

    public $css = [
        'fineuploader-{version}.css'
    ];

    public function init()
    {
        $this->sourcePath = __DIR__ . '/assets';

        foreach ($this->js as &$js) {
            $js = str_replace('{version}', self::version, $js);
        }

        foreach ($this->css as &$css) {
            $css = str_replace('{version}', self::version, $css);
        }

        parent::init();
    }
}
