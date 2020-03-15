<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    	'login' => 'site/login',
    	'login-with-role' => 'site/login-with-role',
		'logout' => 'site/logout',
		'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
];