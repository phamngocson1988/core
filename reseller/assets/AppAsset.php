<?php

namespace reseller\assets;

use yii\web\AssetBundle;

/**
 * Main reseller application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/font-awesome-all.css',
        'css/reset.css',
        'css/layout.css',
        'css/jquery.bxslider.css',
        'css/jquery.fancybox.min.css',
        'css/ti-style.css',
        'css/theme.css',
        'css/overlay.css',
        'css/media.css',
    ];
    public $js = [
        'js/jquery.bxslider.min.js',
        'js/ajax_action.js',
        'js/jquery.fancybox.min.js',
        'js/sweetalert.min.js',
        'js/function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
