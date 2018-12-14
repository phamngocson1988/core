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
        've-chung-toi' => 'site/about',
        'lien-he' => 'site/contact',
        'huong-dan' => 'site/guide',
        'dang-nhap' => 'site/login',
        'dang-xuat' => 'site/logout',
        'captcha' => 'site/captcha',
        'dang-ky' => 'site/signup',
        'kich-hoat-tai-khoan' => 'site/confirm',
        'cau-hoi-thuong-gap' => 'site/faq',
        'yeu-cau-cap-lai-mat-khau' => 'site/request-password-reset',
        'cap-lai-mat-khau' => 'site/reset-password',
        'dang-ky-nhan-tin' => 'subscriber/create',

        // notifications
        'notifications/default/index' => 'notifications/default/index',
        'notifications/default/list' => 'notifications/default/list',
        'notifications/default/count' => 'notifications/default/count',
        'notifications/default/read' => 'notifications/default/read',
        'notifications/default/read-all' => 'notifications/default/read-all',
        'notifications/default/delete-all' => 'notifications/default/delete-all',

        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ],
];