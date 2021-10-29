<?php

namespace payment\assets;

use yii\web\AssetBundle;

/**
 * Main website application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/slick-theme.css',
        'css/slick.css',
        'vendor/intlTelInput/css/intlTelInput.css',
        'css/slimselect.min.css',
        'css/aos.css',
        'css/main.css',
        'css/jquery.fancybox.min.css',
        'css/theme.css',
    ];
    public $js = [
        'js/vendor/modernizr-2.8.3-respond-1.4.2.min.js',
        ['js/vendor/popper.min.js', 'position' => \yii\web\View::POS_HEAD ],
        'js/vendor/slick.min.js',
        'vendor/intlTelInput/js/intlTelInput.js',
        'js/vendor/slimselect.min.js',
        'js/vendor/aos.js',
        'js/vendor/main.js',
        'js/jquery.fancybox.min.js',
        'js/ajax_action.js',
        'js/function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
