<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class AffiliateAccount extends ActiveRecord
{
	public static function tableName()
    {
        return '{{%affiliate_account}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }
}