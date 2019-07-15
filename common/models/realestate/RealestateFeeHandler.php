<?php
namespace common\models\realestate;

use Yii;
use yii\base\Model;

/**
 * PromotionHandler model
 */
abstract class RealestateFeeHandler extends Model
{
    public $title;

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