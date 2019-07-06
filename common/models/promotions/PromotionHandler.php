<?php
namespace common\models\promotions;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * PromotionHandler model
 */
abstract class PromotionHandler extends Model
{
    const EFFECT_USER = 'user';
    const EFFECT_GAME = 'game';
    
    public $title;
    public $promotion_id;
    public $object;

    public static $_effected_objects = [self::EFFECT_USER, self::EFFECT_GAME];

    public function asArray()
    {
        $data = [];
        foreach ($this->safeAttributes() as $attr) {
            $data[$attr] = $this->$attr;
        }
        return $data;
    }

    protected function isSafeAttribute($attr)
    {
        $safe = $this->safeAttributes();
        return in_array($attr, $safe);
    }

    public function getEffectedObject()
    {
        return $this->object;
    }
}