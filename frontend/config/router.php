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
        '<controller>/<action>' => '<controller>/<action>',
    ],
];