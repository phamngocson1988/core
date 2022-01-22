<?php
return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => true,
    'suffix' => '.html',
    'rules' => [
        '/' => 'wallet/index',
        '/o/<token>' => 'wallet/order',
        '/<code>' => 'site/login',
        '<controller>/<action>' => '<controller>/<action>',
    ],
];