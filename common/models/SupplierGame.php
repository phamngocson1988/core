<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class SupplierGame extends ActiveRecord
{
    const STATUS_DISABLE = 'disable';
    const STATUS_ENABLE = 'enable';

    public function behaviors()
    {
        return [
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function tableName()
    {
        return '{{%supplier_game}}';
    }

    public static function primaryKey()
    {
        return ["supplier_id", "game_id"];
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'supplier_id']);
    }

}
