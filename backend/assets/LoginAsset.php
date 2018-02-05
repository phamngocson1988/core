<?php

namespace backend\assets;

use yii\web\AssetBundle;
/**
 * Main backend application asset bundle.
 */
class LoginAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'vendor/assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'vendor/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'vendor/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'vendor/assets/global/plugins/select2/css/select2.min.css',
        'vendor/assets/global/plugins/select2/css/select2-bootstrap.min.css',
        'vendor/assets/global/css/components.min.css',
        'vendor/assets/global/css/plugins.min.css',
        'vendor/assets/pages/css/login.min.css',
    ];
    
    public $js = [
        ['vendor/assets/global/plugins/respond.min.js', 'condition' => 'lt IE 9'],
        ['vendor/assets/global/plugins/excanvas.min.js', 'condition' => 'lt IE 9'],
        ['vendor/assets/global/plugins/ie8.fix.min.js', 'condition' => 'lt IE 9'],

        //BEGIN CORE PLUGINS
        'vendor/assets/global/plugins/js.cookie.min.js',
        'vendor/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'vendor/assets/global/plugins/jquery.blockui.min.js',
        'vendor/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',

        //BEGIN PAGE LEVEL PLUGINS
        'vendor/assets/global/plugins/jquery-validation/js/jquery.validate.min.js',
        'vendor/assets/global/plugins/jquery-validation/js/additional-methods.min.js',
        'vendor/assets/global/plugins/select2/js/select2.full.min.js',

        //BEGIN THEME GLOBAL SCRIPTS
        'vendor/assets/global/scripts/app.min.js',

        //BEGIN PAGE LEVEL SCRIPTS
        'vendor/assets/pages/scripts/login.min.js',
    ];

    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}