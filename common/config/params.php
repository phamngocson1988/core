<?php
return [
    'user.passwordResetTokenExpire' => 3600,
    'country' => require(__DIR__ . '/country.php'),
    'language' => require(__DIR__ . '/language.php'),
    'currency' => require(__DIR__ . '/currency.php'),
    'languages' => [
		// 'en-US' => [
		// 	'code' => 'en-US',
		// 	'title' => 'English',
		// 	'short' => 'EN',
		// ],
		'vi-VN' => [
			'code' => 'vi-VN',
			'title' => 'Vietnamese',
			'short' => 'VI',
		]
	],
    'thumbnails' => ['50x50', '100x100', '150x150', '300x300', '500x500', '940x630', '800x800', '1000x1000'],
];