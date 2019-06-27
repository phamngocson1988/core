<?php
namespace common\models\promotions;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use common\models\PromotionApply;

/**
 * UserUsingRule model
 */
class UserUsingRule extends Model
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
            'total' => 'Số lần sử dụng cho 1 khách hàng',
        ];
    }


    public function isValid($user = null, $game = null)
    {
        $command = PromotionApply::find()->where(['promotion_id' => $this->promotion_id, 'user_id' => $user->id]);
        return $command->count() < $this->total;
    }
}