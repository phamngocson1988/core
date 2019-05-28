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

    /**
     * Return an array of range of dates
     * @param string $from <y-m-d>
     * @param string $to <y-m-d>
     * @return array $dates
     */
    public static function getDateRange($from, $to, $interval = 1, $unit = 'date')
    {

        switch ($unit) {
            case 'year':
                $intervalFormat = sprintf("P%sY", $interval);
                $dateFormat = 'Y-01-01';
                break;        
            case 'month':
                $intervalFormat = sprintf("P%sM", $interval);
                $dateFormat = 'Y-m-01';
                break;
            default: //date
                $intervalFormat = sprintf("P%sD", $interval);
                $dateFormat = 'Y-m-d';
                break;
        }
        $period = new \DatePeriod(
            new \DateTime($from),
            new \DateInterval($intervalFormat),
            new \DateTime($to)
        );
        $dates = [];
        foreach ($period as $key => $value) {
            $dates[] = $value->format($dateFormat);
        }
        if ($unit = 'date') $dates[] = $to;
        return $dates;
    }

    public static function getQuarterRange($from, $to)
    {
        $fromQuarter = ceil(date('m', strtotime($from)) / 3);
        $toQuarter = ceil(date('m', strtotime($to)) / 3);
        $fromQuarterMonth = $fromQuarter * 3 - 2;
        $toQuarterMonth = $toQuarter * 3 - 2;
        $fromQuarterDate = date("Y-$fromQuarterMonth-01", strtotime($from));
        $toQuarterDate = date("Y-$toQuarterMonth-01", strtotime($from));
        return self::getDateRange($fromQuarterDate, $toQuarterDate, 3, 'month');
    }
}
