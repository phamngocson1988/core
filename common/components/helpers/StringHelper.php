<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace common\components\helpers;

use yii\helpers\StringHelper as BaseStringHelper;

/**
 * StringHelper
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @author Alex Makarov <sam@rmcreative.ru>
 * @since 2.0
 */
class StringHelper extends BaseStringHelper
{
	/**
     * Returns a string in a URL friendly format. This function is
     * recommended to be used on non-multibyte character sets. So 
     * this is not recommended for UTF-8, since certain PHP 
     * functions (like strtolower) should not be used on multibyte
     * strings.
     * @param string $str The input string.
     * @return string The URL friendly string.
     */
    public static function generateSlug($text)
    {
        // replace non letter or digits by -
        $text = preg_replace('~[^\pL\d]+~u', '-', $text);

        // transliterate
        // $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

        // remove unwanted characters
        $text = preg_replace('~[^-\w]+~', '', $text);

        // trim
        $text = trim($text, '-');

        // remove duplicate -
        $text = preg_replace('~-+~', '-', $text);

        // lowercase
        $text = strtolower($text);

        if (empty($text)) {
        return 'n-a';
        }

        return $text;
    }

    public static function telecomProviderDetector($phone)
    {
        $providers = [
            'viettel' => ['0162', '0163', '0164', '0165', '0166', '0167', '0168', '0169', '086', '096', '097', '098'],
            'mobifone' => ['0120', '0121', '0122', '0126', '0128', '090', '093', '089'],
            'vinaphone' => ['0123', '0124', '0125', '0127', '0129', '091', '094', '088'],
            'vinamobile' => ['0186', '0188', '092'],
            'gmobile' => ['0199', '099'],
        ];

        foreach ($providers as $provider => $exts) {
            foreach ($exts as $ext) {
                if (preg_match('/^(' . $ext . ')+/', $phone, $matches)) return $provider;
            }
        }
        return 'other';
    }

    public static function separateMessage($message) 
    {
        $len = strlen($message);
        if ($len <=160) return 1;
        if ($len <=306) return 2;
        if ($len <=459) return 3;
        return false;
    }
}
