<?php
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class PaymentCommitmentOrder extends PaymentCommitment implements PaymentCommitmentInterface
{
    public static function find()
	{
		return new PaymentCommitmentOrderQuery(get_called_class());
    }
    
    public function getObject()
    {
        return $this->hasOne(Order::className(), ['id' => 'object_key']);
    }

    public function getObjectKey()
    {
        $object = $this->object;
        return $object->getId();
    }
}

class PaymentCommitmentOrderQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['object_name' => PaymentCommitmentOrder::OBJECT_NAME_ORDER]);
        parent::init();
    }
}