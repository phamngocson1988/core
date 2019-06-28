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
    public $title;
    public $promotion_id;

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
}