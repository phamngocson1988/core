<?php
namespace common\models\promotions;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * Promotion model
 */
class TotalUsingRule extends Model
{
    public $title;
    public $label;
    public $promotion_id;
    public $total;

    public function scenarios()
    {
        return [
            self::SCENARIO_DEFAULT => ['total'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'total' => 'Số lần sử dụng',
        ];
    }


    public function isValid($user = null, $game = null)
    {
        $command = PromotionApply::find()->where(['promotion_id' => $this->promotion_id]);
        return $command->count() < $this->total;
    }
}