<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\helpers;

use yii\helpers\FileHelper as BaseFileHelper;
/**
 * File system helper
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alex Makarov <sam@rmcreative.ru>
 * @since 2.0
 */
class DateHelper extends BaseFileHelper
{
	public static function ranges($from, $to)
    {
    	$begin = new DateTime($from);
		$end = new DateTime($to);
		$end = $end->modify( '+1 day' ); 
        $period = new DatePeriod(
			$begin,
			new DateInterval('P1D'),
			$end;
		);
		$ranges = [];
		foreach ($period as $key => $value) {
		    $ranges[] = $value->format('Y-m-d')   ;    
		}
		return $ranges
    }
}
