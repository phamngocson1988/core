<?php
return [
	'cart' => [
        'class' => 'yii2mod\cart\Cart',
        // you can change default storage class as following:
        'storageClass' => [
        	// need to run: php yii migrate --migrationPath=@vendor/yii2mod/yii2-cart/migrations
            'class' => 'yii2mod\cart\storage\DatabaseStorage',
            // you can also override some properties 
            'deleteIfEmpty' => true
        ]
    ],
];