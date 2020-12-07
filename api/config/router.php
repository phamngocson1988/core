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
    ],
];