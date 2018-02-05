<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    	'login' => 'site/login',
		'logout' => 'site/logout',
		'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
];