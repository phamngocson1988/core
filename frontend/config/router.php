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

        'danh-muc-bai-viet/<slug>' => 'post/index',
        'danh-muc-bai-viet' => 'post/index',
        'bai-viet/<slug>' => 'post/read',

        'san-pham' => 'product/index',
        'san-pham/<slug>' => 'product/read/<slug:\w+>',

        'khuyen-mai' => 'promotion/index',
        'khuyen-mai/<slug>' => 'promotion/read/<slug:\w+>',

        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ],
];