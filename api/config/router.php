<?php
return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
        'login' => 'site/login',
        'me' => 'profile/me',
        'games' => 'game/index',
        'game/<id:\d+>' => 'game/view',
        'user/search' => 'user/search',
        'order/<id:\d+>' => 'order/view',
        'order/cancel/<id:\d+>' => 'order/cancel',
        'checkout/<id:\d+>' => 'cart/checkout',
    ],
];