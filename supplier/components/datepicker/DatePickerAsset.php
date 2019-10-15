<?php
/**
 * @copyright Copyright (c) 2013-2016 2amigOS! Consulting Group LLC
 * @link http://2amigos.us
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
namespace supplier\components\datepicker;

use yii\web\AssetBundle;

/**
 * DatePickerAsset
 *
 * @author Antonio Ramirez <amigo.cobos@gmail.com>
 * @link http://www.ramirezcobos.com/
 * @link http://www.2amigos.us/
 * @package dosamigos\datepicker
 */
class DatePickerAsset extends AssetBundle
{
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'vendor/assets/global/plugins/bootstrap-datepicker/css/bootstrap-datepicker.min.css'
    ];

    public $js = [
        'vendor/assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.min.js',
    ];

    public $depends = [
        '\supplier\assets\AppAsset'
    ];
}
