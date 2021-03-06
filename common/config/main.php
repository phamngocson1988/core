<?php
return [
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'name' => 'Core Functions',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'view' => [
            'class' => 'yii\web\View',
            'renderers' => [
                'tpl' => [
                    'class' => 'yii\smarty\ViewRenderer',
                    'cachePath' => '@runtime/Smarty/cache',
                ],
            ],
        ],
        'authManager' => [
            'class' => 'yii\rbac\DbManager',
        ],
        'settings' => [
            'class' => 'yii2mod\settings\components\Settings',
            'cache' => [
                'class' => 'yii\caching\FileCache',
                'cachePath' => '@common/runtime/cache'
            ]
        ],
        'i18n' => [
            'translations' => [
                'yii2mod.settings' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@yii2mod/settings/messages',
                ],
            ],
        ],
        'image' => [
            'class' => 'common\components\filesystem\ImageSystemManager',
            'generate_thumbnail' => true,
            'thumbnails' => ['50x50', '100x100', '150x150', '300x300', '500x500', '420x550', '71x71', '188x246', '270x400'],
            'dependency' => [
                'class' => 'common\components\filesystem\local\LocalImageSystem',
                // 'image_path' => '@common/uploads/images',
                // 'image_url' => $_SERVER['HTTP_HOST'],
            ]
        ],
        'file' => [
            'class' => 'common\components\filesystem\FileSystemManager',
            'dependency' => [
                // 'class' => 'common\components\filesystem\cloudinary\CloudinaryFileSystem',
                // 'cloud_name' => 'sonpham',
                // 'api_key' => '365324843952423',
                // 'api_secret' => 'kvaLj2sSrKLJvYkrR4xo_2bq2F4',
                // 'folder' => 'eagleland'

                'class' => 'common\components\filesystem\local\LocalFileSystem',
                'file_path' => '@common/uploads/files',
                // 'file_url' => 'http://file.kinggerm.com',
            ]
        ],
    ],
];
