<?php
namespace common\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

class TransactionHistory extends ActiveRecord
{
    const TYPE_INPUT = 'I';
    const TYPE_OUTPUT = 'O';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%transaction_history}}';
    }

    /**
     * @inheritdoc
     */
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

    public function getCustomer()
    {
        return $this->hasOne(Customer::className(), ['id' => 'customer_id']);
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_INPUT => 'Nạp tiền',
            self::TYPE_OUTPUT => 'Rút tiền'
        ];
    }

    public function getTypeLabel()
    {
        $list = self::getTypeList();
        return ArrayHelper::getValue($list, $this->transaction_type);
    }
}
