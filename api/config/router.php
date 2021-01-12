<?php
return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        'login' => 'site/login',
        'me' => 'user/me',
        'games' => 'game/index',
        'game/<id:\d+>' => 'game/view',
        'order/<id:\d+>' => 'order/view',
        'order/cancel/<id:\d+>' => 'order/cancel',
        'checkout/<id:\d+>' => 'cart/checkout',
    ],
];