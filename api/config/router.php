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
        'order/send-complain/<id:\d+>' => 'order/send-complain',
        'order/list-complain/<id:\d+>' => 'order/list-complain',
        'order/confirm' => 'order/move-to-confirmed',
        'order/create/<id:\d+>' => 'order/create',
        'order/pay/<id:\d+>' => 'order/pay',
        'checkout/<id:\d+>' => 'cart/checkout',
    ],
];