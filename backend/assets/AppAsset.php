<?php

namespace backend\assets;

use yii\web\AssetBundle;

/**
 * Main backend application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'https://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all',
        'vendor/assets/global/plugins/font-awesome/css/font-awesome.min.css',
        'vendor/assets/global/plugins/simple-line-icons/simple-line-icons.min.css',
        'vendor/assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css',
        'vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css',
        'vendor/assets/global/plugins/morris/morris.css',
        'vendor/assets/global/plugins/fullcalendar/fullcalendar.min.css',
        'vendor/assets/global/plugins/jqvmap/jqvmap/jqvmap.css',
        // 'vendor/assets/global/plugins/select2/css/select2.min.css',
        // 'vendor/assets/global/plugins/select2/css/select2-bootstrap.min.css',
        ['vendor/assets/global/css/components.min.css', 'id' => 'style_components'],
        'vendor/assets/global/css/plugins.min.css',
        'vendor/assets/layouts/layout/css/layout.min.css',
        ['vendor/assets/layouts/layout/css/themes/darkblue.min.css', 'id' => 'style_color'],
        'vendor/assets/layouts/layout/css/custom.min.css',
        'css/theme_custom.css',
        'vendor/assets/global/plugins/bootstrap-sweetalert/sweetalert.css',
        'vendor/assets/global/plugins/bootstrap-toastr/toastr.min.css'
    ];
    public $js = [
        ['vendor/assets/global/plugins/respond.min.js', 'condition' => 'lt IE 9'],
        ['vendor/assets/global/plugins/excanvas.min.js', 'condition' => 'lt IE 9'],
        ['vendor/assets/global/plugins/ie8.fix.min.js', 'condition' => 'lt IE 9'],

        //BEGIN CORE PLUGINS
        'vendor/assets/global/plugins/js.cookie.min.js',
        'vendor/assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js',
        'vendor/assets/global/plugins/jquery.blockui.min.js',
        'vendor/assets/global/plugins/bootstrap-toastr/toastr.min.js',
        'vendor/assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js',

        //BEGIN PAGE LEVEL PLUGINS
        'vendor/assets/global/plugins/moment.min.js',
        'vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js',
        'vendor/assets/global/plugins/morris/morris.min.js',
        'vendor/assets/global/plugins/morris/raphael-min.js',
        'vendor/assets/global/plugins/counterup/jquery.waypoints.min.js',
        'vendor/assets/global/plugins/counterup/jquery.counterup.min.js',
        'vendor/assets/global/plugins/amcharts/amcharts/amcharts.js',
        'vendor/assets/global/plugins/amcharts/amcharts/serial.js',
        'vendor/assets/global/plugins/amcharts/amcharts/pie.js',
        'vendor/assets/global/plugins/amcharts/amcharts/radar.js',
        'vendor/assets/global/plugins/amcharts/amcharts/themes/light.js',
        'vendor/assets/global/plugins/amcharts/amcharts/themes/patterns.js',
        'vendor/assets/global/plugins/amcharts/amcharts/themes/chalk.js',
        'vendor/assets/global/plugins/amcharts/ammap/ammap.js',
        'vendor/assets/global/plugins/amcharts/ammap/maps/js/worldLow.js',
        'vendor/assets/global/plugins/amcharts/amstockcharts/amstock.js',
        'vendor/assets/global/plugins/fullcalendar/fullcalendar.min.js',
        'vendor/assets/global/plugins/horizontal-timeline/horizontal-timeline.js',
        'vendor/assets/global/plugins/flot/jquery.flot.min.js',
        'vendor/assets/global/plugins/flot/jquery.flot.resize.min.js',
        'vendor/assets/global/plugins/flot/jquery.flot.categories.min.js',
        'vendor/assets/global/plugins/jquery-easypiechart/jquery.easypiechart.min.js',
        'vendor/assets/global/plugins/jquery.sparkline.min.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/jquery.vmap.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.russia.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.world.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.europe.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.germany.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/maps/jquery.vmap.usa.js',
        'vendor/assets/global/plugins/jqvmap/jqvmap/data/jquery.vmap.sampledata.js',
        // 'vendor/assets/global/plugins/select2/js/select2.full.min.js',
        'vendor/assets/global/plugins/bootstrap-sweetalert/sweetalert.min.js',

        //BEGIN THEME GLOBAL SCRIPTS
        'vendor/assets/global/scripts/app.min.js',
        
        //BEGIN PAGE LEVEL SCRIPTS
        'vendor/assets/pages/scripts/dashboard.min.js',
        // 'vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
        // 'vendor/assets/pages/scripts/components-date-time-pickers.min.js',

        //BEGIN THEME LAYOUT SCRIPTS
        'vendor/assets/layouts/layout/scripts/layout.min.js',
        'vendor/assets/layouts/layout/scripts/demo.min.js',
        'vendor/assets/layouts/global/scripts/quick-sidebar.min.js',
        'vendor/assets/layouts/global/scripts/quick-nav.min.js',
        'js/ajax_action.js',
        'js/function.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];
}
