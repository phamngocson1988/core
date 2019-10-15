<?php
return [
    'class' => 'yii\web\UrlManager',
    'enablePrettyUrl' => true,
    'showScriptName' => false,
    'rules' => [
    	'login' => 'site/login',
		'logout' => 'site/logout',

		// notifications
        // 'notifications/default/index' => 'notifications/index',
        // 'notifications/default/list' => 'notifications/list',
        // 'notifications/default/count' => 'notifications/count',
        // 'notifications/default/read' => 'notifications/read',
        // 'notifications/default/read-all' => 'notifications/read-all',
        // 'notifications/default/delete-all' => 'notifications/delete-all',

		'<controller:\w+>/<action:\w+>' => '<controller>/<action>',
    ]
];