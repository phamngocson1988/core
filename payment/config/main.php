<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-payment',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'payment\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-payment',
            'parsers' => [
                'application/json' => 'yii\web\JsonParser',
            ]
    
        ],
        'user' => [
            'identityClass' => 'payment\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-payment', 'httpOnly' => true],
            'loginUrl' => ['site/index'],
        ],
        'view' => [
            'class' => 'yii\web\View',
            'title' => 'Payment-Top Up Service',
        ],
        'session' => [
            'name' => 'advanced-payment',
            'timeout' => 3600
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning', 'info'],
                ],
            ],
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'js/vendor/jquery-3.4.1.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'css' => [
                        'css/bootstrap.min.css',
                    ],
                    'js' => [
                        'js/vendor/bootstrap.min.js'
                    ],
                    'depends' => ['yii\web\YiiAsset']
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'urlManager' => require('router.php'),
    ],
    'params' => $params,
];
