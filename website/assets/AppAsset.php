<?php

namespace website\assets;

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
        'css/intlTelInput.css',
        'css/slimselect.min.css',
        'css/aos.css',
        'css/main.css',
        'css/theme.css',
    ];
    public $js = [
        'js/vendor/modernizr-2.8.3-respond-1.4.2.min.js',
        'js/vendor/popper.min.js',
        'js/vendor/slick.min.js',
        'js/vendor/intlTelInput.js',
        'js/vendor/slimselect.min.js',
        'js/vendor/aos.js',
        'js/vendor/main.js',



        
        'js/ajax_action.js',
        'js/function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
