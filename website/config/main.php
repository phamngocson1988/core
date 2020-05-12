<?php
$params = array_merge(
    require __DIR__ . '/../../common/config/params.php',
    require __DIR__ . '/../../common/config/params-local.php',
    require __DIR__ . '/params.php',
    require __DIR__ . '/params-local.php'
);

return [
    'id' => 'app-website',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'website\controllers',
    'components' => [
        'request' => [
            'csrfParam' => '_csrf-website',
        ],
        'user' => [
            'identityClass' => 'website\models\User',
            'enableAutoLogin' => true,
            'identityCookie' => ['name' => '_identity-website', 'httpOnly' => true],
            'loginUrl' => ['site/login'],
        ],
        'view' => [
            'class' => 'website\components\web\View',
            'title' => 'Kinggems-Top Up Mobile Game Service',
            'on afterRender' => function($event) {
                $view = $event->sender;
                $view->registerMetaTag(['property' => 'og:type', 'content' => 'website'], 'og:type');
                $view->registerMetaTag(['property' => 'og:url', 'content' => \yii\helpers\Url::current([], true)], 'og:url');
                if (!isset($view->metaTags['og:image'])) {
                    $view->registerMetaTag(['property' => 'og:image', 'content' => Yii::$app->settings->get('ApplicationSettingForm', 'logo', '/images/logo.png')], 'og:image');
                }
                if (!isset($view->metaTags['og:title'])) {
                    $view->registerMetaTag(['property' => 'og:title', 'content' => $view->title], 'og:title');
                }
                if (!isset($view->metaTags['og:description'])) {
                    $view->registerMetaTag(['property' => 'og:description', 'content' => 'Kinggems US website is a market of games, providers and customers'], 'og:description');
                }
            }
        ],
        'session' => [
            // this is the name of the session cookie used for login on the website
            'name' => 'advanced-website',
            'timeout' => 3600
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
        'cart' => [
            'class' => 'website\components\cart\Cart',
            // you can change default storage class as following:
            'storageClass' => [
                'class' => 'yii2mod\cart\storage\SessionStorage',
                // you can also override some properties 
                // 'deleteIfEmpty' => true
            ]
        ],
        'kingcoin' => [
            'class' => 'website\components\kingcoin\Cart',
            'storageClass' => [
                'class' => 'yii2mod\cart\storage\DatabaseStorage',
                'deleteIfEmpty' => true
            ]
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'viewPath' => '@website/mail',
        ],
        'sms' => [
            'viewPath' => '@website/sms',
        ],
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
    'params' => $params,
];
