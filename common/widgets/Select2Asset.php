<?php
/**
 * @link https://github.com/2amigos/yii2-selectize-widget
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @license http://opensource.org/licenses/BSD-3-Clause
 */

namespace common\widgets;

use yii\web\AssetBundle;

/**
 * Select2Asset
 *
 * @author Son Pham
 */
class Select2Asset extends AssetBundle
{
    // public $sourcePath = '@bower/selectize/dist';

    public $basePath = '@webroot';
    public $baseUrl = '@web';

    public $css = [
        'vendor/assets/global/plugins/select2/css/select2.min.css',
        'vendor/assets/global/plugins/select2/css/select2-bootstrap.min.css',
    ];
    public $js = [
        'vendor/assets/global/plugins/select2/js/select2.full.min.js',
    ];
    public $depends = [
        'yii\bootstrap\BootstrapAsset',
        'yii\web\JqueryAsset',
    ];
}
