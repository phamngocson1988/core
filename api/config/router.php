<?php
return [
    'enablePrettyUrl' => true,
    'enableStrictParsing' => true,
    'showScriptName' => false,
    'rules' => [
     //    ['class' => 'yii\rest\UrlRule', 
     //    	'controller' => ['site'],
	    //     'extraPatterns' => [
	    //         'GET login' => 'login',
	    //     ],
	    // ],
        '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ],
];