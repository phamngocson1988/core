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
        'login' => 'site/login',
        'logout' => 'site/logout',
        'captcha' => 'site/captcha',
        'signup' => 'site/signup',
        'register' => 'site/register',
        'activate' => 'site/activate',
        'reset-password' => 'site/reset-password',
        'about' => 'site/about',
        'contact' => 'site/contact',
        'advertise' => 'site/advertise',
        'corporate' => 'site/corporate',

        'operator' => 'operator/index',
        'operator/<id:\d+>-<slug:[\w\-]+>' => 'operator/view',

        'news' => 'news/index',
        'bonus' => 'bonus/index',

        'news/category/<id:\d+>-<slug:[\w\-]+>' => 'news/category',
        'news/operator/<id:\d+>-<slug:[\w\-]+>' => 'news/operator',
        'news/<id:\d+>-<slug:[\w\-]+>' => 'news/view',

        'forum' => 'forum/index',
        'forum/topic/<id:\d+>-<slug:[\w\-]+>' => 'forum/topic',
        'forum/<id:\d+>-<slug:[\w\-]+>' => 'forum/category',

        'complain' => 'complain/index',
        'complain/operator/<id:\d+>-<slug:[\w\-]+>' => 'complain/operator',
        'complain/<id:\d+>-<slug>' => 'complain/view',

        'manage/<operator_id:\d+>-<slug:[\w\-]+>/dashboard' => 'manage-operator/index',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/edit' => 'manage-operator/edit',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/update-avatar' => 'manage-operator/update-avatar',

        'manage/<operator_id:\d+>-<slug:[\w\-]+>/review' => 'manage-review/index',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/review/list' => 'manage-review/list',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/review/reply' => 'manage-review/reply',

        'manage/<operator_id:\d+>-<slug:[\w\-]+>/complain' => 'manage-complain/index',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/complain/list' => 'manage-complain/list',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/complain/assign' => 'manage-complain/assign',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/complain/view/<id:\d+>' => 'manage-complain/view',

        'member/<username>/dashboard' => 'member/index',
        '<controller>/<action>' => '<controller>/<action>',
    ],
];