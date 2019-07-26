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
        'css/font-awesome-all.css',
        'css/layout.css',
        'css/jquery.bxslider.css',
        'css/jquery.fancybox.min.css',
        'css/theme.css',
    ];
    public $js = [
        'js/jquery.bxslider.min.js',
        'js/ajax_action.js',
        'js/jquery.fancybox.min.js',
        'js/function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
