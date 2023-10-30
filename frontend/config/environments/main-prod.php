<?php

return
    [
        'components' => [
            'log' => [
                'traceLevel' => 3,
                'targets' => [
                    [
                        'class' => 'yii\log\FileTarget',
                        'levels' => ['error', 'warning'],
                        'maxLogFiles' => 100,
                    ],
                ],
            ],
        ],
    ];