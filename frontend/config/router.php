<?php
return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => true,
    'suffix' => '.html',
    'rules' => [
        '/' => 'site/index',
        'about-us' => 'site/about',
        'contact-us' => 'site/contact',
        'guide' => 'site/guide',
        'login' => 'site/login',
        'logout' => 'site/logout',
        'captcha' => 'site/captcha',
        'signup' => 'site/signup',
        'activate' => 'site/activate',
        'faq' => 'site/question',
        'request-password-reset' => 'site/request-password-reset',
        'reset-password' => 'site/reset-password',
        'subscribe' => 'subscriber/create',

        // game
        'game/<id:\d+>-<slug:[\w\-]+>' => 'game/view',
        'shop' => 'game/index', 

        // cart
        'shopping-cart' => 'cart/index',
        'checkout' => 'cart/checkout',
        'purchase' => 'cart/purchase',
        'purchase-success' => 'cart/success',
        'purchase-cancel' => 'cart/error',
        //user
        'user/dashboard' => 'user/index',

        // payment
        'pricing/<identifier>/verify' => 'pricing/verify',
        'pricing/<identifier>/cancel' => 'pricing/cancel',

        // topup
        'topup' => 'topup/index',

        // promotion
        'promotion' => 'promotion/index',

        // refer
        'refer-friend' => 'refer/index',

        // affiliate
        'affiliate' => 'affiliate/index',

        // notifications
        'notifications/default/index' => 'notifications/default/index',
        'notifications/default/list' => 'notifications/default/list',
        'notifications/default/count' => 'notifications/default/count',
        'notifications/default/read' => 'notifications/default/read',
        'notifications/default/read-all' => 'notifications/default/read-all',
        'notifications/default/delete-all' => 'notifications/default/delete-all',

        '<controller>/<action>' => '<controller>/<action>',
    ],
];