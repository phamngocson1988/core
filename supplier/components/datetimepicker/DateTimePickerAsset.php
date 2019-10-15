<?php
/**
 * @copyright Copyright (c) 2013-2017 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace backend\components\datetimepicker;

use yii\web\AssetBundle;

/**
 * DateTimePickerAsset
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\datetimepicker
 */
class DateTimePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendor/assets/global/plugins/bootstrap-datetimepicker/css/bootstrap-datetimepicker.min.css'
    ];

    public $js = [
        'vendor/assets/global/plugins/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js',
        // 'vendor/assets/pages/scripts/components-date-time-pickers.min.js',
    ];

    public $depends = [
        '\backend\assets\AppAsset'
    ];
}
