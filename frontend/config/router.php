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
        'operator/<id:\d+>-<slug:[\w\-]+>' => 'operator/view',

        'news' => 'news/index',
        'news/category/<id:\d+>-<slug:[\w\-]+>' => 'news/category',
        'news/operator/<id:\d+>-<slug:[\w\-]+>' => 'news/operator',
        'news/<id:\d+>-<slug:[\w\-]+>' => 'news/view',

        'manage/<operator_id:\d+>-<slug:[\w\-]+>/dashboard' => 'manage/index',
        'manage/<operator_id:\d+>-<slug:[\w\-]+>/<action>' => 'manage/<action>',

        'member/<username>/dashboard' => 'member/index',
        '<controller>/<action>' => '<controller>/<action>',
    ],
];