<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-backend',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'backend\controllers',
    'layout' => 'main.php',
    'defaultRoute' => 'site',
    'bootstrap' => [
        'log', 
    ],
    'modules' => [
        'notifications' => [
            'class' => 'webzop\notifications\Module',
            'channels' => [
                'screen' => [
                    'class' => 'webzop\notifications\channels\ScreenChannel',
                ],
                'desktop' => [
                    'class' => 'backend\components\notifications\channels\DesktopNotificationChannel'
                ]
            ],
        ],
    ],
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-backend',            
        ],
        'user' => [
            'class' => 'backend\components\User',
            'identityClass' => 'backend\models\User', 
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-backend', 'httpOnly' => true],
            'on afterLogin' => ['backend\events\LoginHandler', 'logLogin'],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the backend
            'name' => 'advanced-backend',
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
                'yii2mod\slider\IonSliderAsset' => [
                    'css' => [
                        'css/normalize.css',
                        'css/ion.rangeSlider.css',
                        'css/ion.rangeSlider.skinFlat.css'
                     ]
                ],
            ],
        ],

        'urlManager' => require('router.php'),
        'urlManagerFrontend' => require('../../frontend/config/router.php'),
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@backend/messages',
                    'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
];
