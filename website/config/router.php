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
        'signup/refer/<refer:[\w]+>' => 'site/signup',
        'signup/affiliate/<affiliate:[\w]+>' => 'site/signup',
        'signup' => 'site/signup',
        'request-access' => 'site/request-access',
        // 'register/refer/<refer:[\w]+>' => 'site/register',
        // 'register/affiliate/<affiliate:[\w]+>' => 'site/register',
        // 'register' => 'site/register',
        'activate' => 'site/activate',
        'faq' => 'site/question',
        'faq/cat/<id:\d+>-<slug:[\w\-]+>' => 'site/question-category',
        'faq/detail/<id:\d+>-<slug:[\w\-]+>' => 'site/question-detail',
        'request-password-reset' => 'site/request-password-reset',
        'reset-password' => 'site/reset-password',
        'subscribe' => 'subscriber/create',

        // game
        'game/<id:\d+>-<slug:[\w\-]+>' => 'game/view',
        'shop' => 'game/index', 
        'shop/<id:\d+>-<slug:[\w\-]+>' => 'shop/view',

        // cart
        'shopping-cart' => 'cart/index',
        'checkout' => 'cart/checkout',
        'purchase' => 'cart/purchase',
        'purchase-success' => 'cart/success',
        'purchase-cancel' => 'cart/cancel',
        'purchase-error' => 'cart/error',
        'payment-success' => 'cart/payment-success',
        'payment-coin-base-callback' => 'cart/payment-coin-base-callback',
        'payment-webmoney-callback' => 'cart/payment-webmoney-callback',
        'payment-binance-callback' => 'cart/payment-binance-callback',
        
        //user
        'user/dashboard' => 'user/index',

        // topup
        'topup' => 'topup/index',

        // promotion
        'promotion' => 'promotion/index',
        'promotion/<id:\d+>-<slug:[\w\-]+>' => 'promotion/view',

        // refer
        'referral' => 'referral/index',

        // affiliate
        'affiliate' => 'affiliate/index',

        // notifications
        'notifications' => 'notification/index',
        'message-notifications' => 'message-notification/index',
        'notifications/default/index' => 'notifications/default/index',
        'notifications/default/list' => 'notifications/default/list',
        'notifications/default/count' => 'notifications/default/count',
        'notifications/default/read' => 'notifications/default/read',
        'notifications/default/read-all' => 'notifications/default/read-all',
        'notifications/default/delete-all' => 'notifications/default/delete-all',

        // post
        'news' => 'post/index',
        'news/<id:\d+>-<slug:[\w\-]+>' => 'post/view',
        'news/category/<id:\d+>-<slug:[\w\-]+>' => 'post/category',

        '<controller>/<action>' => '<controller>/<action>',
    ],
];