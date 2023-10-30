<?php

namespace webtoucher\cookie;

use yii\web\AssetBundle as BaseAssetBundle;

/**
 * Asset Bundle with JavaScript Cookie.
 */
class AssetBundle extends BaseAssetBundle
{
    public $sourcePath = '@bower/js-cookie/src';

    public $js = [
        'js.cookie.js',
    ];

    public $depends = [
        'yii\web\JqueryAsset',
    ];
}
