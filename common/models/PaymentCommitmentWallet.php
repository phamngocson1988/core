<?php 
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

class PaymentCommitmentWallet extends PaymentCommitment implements PaymentCommitmentInterface
{
    public static function find()
	{
		return new PaymentCommitmentWalletQuery(get_called_class());
    }

    public function getObject()
    {
        return $this->hasOne(PaymentTransaction::className(), ['id' => 'object_key']);
    }

    public function getObjectKey()
    {
        $object = $this->object;
        return $object->getId();
    }
}

class PaymentCommitmentWalletQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['object_name' => PaymentCommitmentWallet::OBJECT_NAME_WALLET]);
        parent::init();
    }
}