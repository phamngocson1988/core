<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\helpers;

use yii\helpers\FormatConverter as BaseFormatConverter;
/**
 * File system helper
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alex Makarov <sam@rmcreative.ru>
 * @since 2.0
 */
class FormatConverter extends BaseFormatConverter
{
    /**
     * Convert a time string to timestamp
     * @param $str string of time
     * @param $format the format of time string. If null, it will take default value
     */
	public static function convertToTimeStamp($str, $format = null)
    {
        if (!$str) return;
        if (!$format) $format = \Yii::$app->params['date_format'];
        return \DateTime::createFromFormat('!' . $format, $str)->getTimestamp();
    }

    /**
     * Convert a timestamp value to time string
     * @param $timestamp int value of timestamp
     * @param $format string define datetime format. If null, it will take default value
     */
    public static function convertToDate($timestamp, $format = null)
    {
        if (!$timestamp) return;
        if (!$format) $format = \Yii::$app->params['date_format'];
        return date($format, $timestamp);
    }

    public static function countDuration($seconds)
    {
        $hourSecond = 3600;
        $minuteSecond = 60;
        $hour = floor($seconds / $hourSecond);
        $minute = floor(($seconds % $hourSecond) / $minuteSecond);
        $second = $seconds - ($hour * $hourSecond) - ($minute * $minuteSecond);
        return sprintf("%s:%s:%s", str_pad($hour, 2, "0", STR_PAD_LEFT), str_pad($minute, 2, "0", STR_PAD_LEFT), str_pad($second, 2, "0", STR_PAD_LEFT));
    }
}
