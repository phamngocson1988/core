<?php
namespace reseller\components\helpers;

use Yii;

class Url extends \yii\helpers\Url
{

	public static function to($url = '', $scheme = false)
    {
        if (is_array($url)) {
        	$url['reseller_code'] = Yii::$app->getRequest()->get('reseller_code');
            return static::toRoute($url, $scheme);
        }

        $url = Yii::getAlias($url);
        if ($url === '') {
            $url = Yii::$app->getRequest()->getUrl();
        }

        if ($scheme === false) {
            return $url;
        }

        if (static::isRelative($url)) {
            // turn relative URL into absolute
            $url = static::getUrlManager()->getHostInfo() . '/' . ltrim($url, '/');
        }

        return static::ensureScheme($url, $scheme);
    }
}
