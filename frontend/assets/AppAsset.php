<?php

namespace frontend\assets;

use yii\web\AssetBundle;

/**
 * Main frontend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome.min.css',
        'css/style.css',
    ];
    public $js = [
        'js/a076d05399.js',
        'js/popper.min.js',
        'js/jquery.matchHeight-min.js',
        'js/slick.min.js',
        'js/ajax_action.js',
        // 'js/function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
