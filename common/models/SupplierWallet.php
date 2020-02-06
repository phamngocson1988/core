<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

class SupplierWallet extends ActiveRecord
{
    const TYPE_INPUT = "I";
    const TYPE_OUTPUT = "O";

    CONST STATUS_PENDING = "pending";
    const STATUS_COMPLETED = "completed";

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
        return '{{%supplier_wallet}}';
    }

    public function getSupplier()
    {
        return $this->hasOne(Supplier::className(), ['user_id' => 'supplier_id']);
    }
}
