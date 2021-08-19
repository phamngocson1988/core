<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    	'login' => 'site/login',
    	'forgot-password' => 'site/request-password-reset',
      'reset-password' => 'site/reset-password',
		'logout' => 'site/logout',
		'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
];