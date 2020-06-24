<?php
$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-supplier',
    'basePath' => dirname(__DIR__),
    'controllerNamespace' => 'supplier\controllers',
    'layout' => 'main.tpl',
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
            ],
        ],
    ],
	'language' => 'vi',
	'sourceLanguage' => 'en-US',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-supplier',            
        ],
        'user' => [
            'identityClass' => 'supplier\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-supplier', 'httpOnly' => true],
        ],
        'session' => [
            // this is the name of the session cookie used for login on the supplier
            'name' => 'advanced-supplier',
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
        'urlManagerBackend' => require('../../backend/config/router.php'),
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@supplier/mail/kinggems',
        ],
        'supplier_mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@supplier/mail/supplier',
        ],
    ],
    'params' => $params,
];
