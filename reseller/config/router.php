<?php
return [
    'class' => 'yii\web\UrlManager',
    'baseUrl' => '',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'enableStrictParsing' => true,
    'suffix' => '.html',
    'rules' => [
        '/<reseller_code:[\w\-]+>' => 'game/index',

        // game
        '<reseller_code:[\w\-]+>/game/<id:\d+>-<slug:[\w\-]+>' => 'game/view',
        '<reseller_code:[\w\-]+>/shop' => 'game/index', 

        // cart
        '<reseller_code:[\w\-]+>/shopping-cart' => 'cart/index',
        '<reseller_code:[\w\-]+>/checkout' => 'cart/checkout',
        '<reseller_code:[\w\-]+>/purchase' => 'cart/purchase',
        '<reseller_code:[\w\-]+>/purchase-success' => 'cart/success',
        '<reseller_code:[\w\-]+>/purchase-cancel' => 'cart/cancel',
        '<reseller_code:[\w\-]+>/purchase-error' => 'cart/error',
        
        '<reseller_code:[\w\-]+>/<controller>/<action>' => '<controller>/<action>',
    ],
];