<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-client',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'client\controllers',
    'layout' => 'main.tpl',
    'bootstrap' => ['log'],
    'modules' => [],
    'language' => 'vi',
    'sourceLanguage' => 'en-US',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-client',
        ],
        'user' => [
            'identityClass' => 'common\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-client', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the client
            'name' => 'advanced-client',
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'assetManager' => [
            'bundles' => [
                'yii\web\JqueryAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'js' => [
                        'vendor/assets/global/plugins/jquery.min.js',
                    ]
                ],
                'yii\bootstrap\BootstrapAsset' => [
                    'sourcePath' => null,
                    'basePath' => '@webroot',
                    'baseUrl' => '@web',
                    'css' => [
                        'vendor/assets/global/plugins/bootstrap/css/bootstrap.min.css',
                    ],
                    'js' => [
                        'vendor/assets/global/plugins/bootstrap/js/bootstrap.min.js'
                    ],
                    'depends' => ['yii\web\YiiAsset']
                ],
            ],
        ],

        'urlManager' => require('router.php'),
        'urlManagerFrontend' => require('../../frontend/config/router.php'),
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@client/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
        'image' => [
            'class' => 'common\components\uploadfiles\standard\ImageHandler',
            'image_path' => '@common/uploads/images',
            'image_url' => 'http://image.core.com',
            // 'file_path' => '@common/uploads/files',
            // 'file_url' => 'http://file.chuchu.com',
        ],
    ],
    'params' => $params,
];
