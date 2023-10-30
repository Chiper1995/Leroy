<?php

return
    [
        'env.code' => 'prod',

        // cache settings -if APC is not loaded, then use DbCache
        'cache' => extension_loaded('apc') ?
            [
                'class' => \yii\caching\ApcCache::className(),
            ] :
            [
                'class' => \yii\caching\DbCache::className(),
                'db' => 'db',
                'cacheTable' => '{{%cache}}',
            ],
    ];