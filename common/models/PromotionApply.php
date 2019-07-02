<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * Game model
 *
 * @property integer $id
 * @property integer $game_id
 * @property integer $image_id
 */
class PromotionApply extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%promotion_apply}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }
}