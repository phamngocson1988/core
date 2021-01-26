<?php

namespace common\components\helpers;

use Yii;

class TimeHelper {
    /**
     * Get difference between two dates
     * @param string $from : the date we calculate from
     * @param string $to: th date we calculate to
     * @param string $unit: support 'second' (default), 'minute', 'hour', 'day'
     */
    public static function timeDiff($from, $to, $unit = 'second') 
    {
        try {
            $seconds = strtotime($from) - $strtotime($to);
            switch ($unit) {
                case 'minute':
                    return $seconds / 60;
                case 'hour':
                    return $seconds / 3600;
                case 'day':
                    return $seconds / 86400;
                default: 
                    return $seconds;
            }
        } catch (\Exception $e) {
            return 0;
        }
    }
}