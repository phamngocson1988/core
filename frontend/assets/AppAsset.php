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
        'js/google_pay.js',
        ["https://pay.google.com/gp/p/js/pay.js", "onload" => "onGooglePayLoaded()", "async" => "async"]
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
