<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\OperatorMeta;

class CreateOperatorMetaForm extends Model
{
	public $product;
    public $deposit_method;
    public $withdrawal_method;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product', 'deposit_method', 'withdrawal_method'], 'trim'],
        ];
    }


    public function attributeLabels()
    {
        return [
            'product' => Yii::t('app', 'product'),
            'deposit_method' => Yii::t('app', 'deposit_method'),
            'withdrawal_method' => Yii::t('app', 'withdrawal_method'),
        ];
    }

    public function create()
    {
        $product = $this->getProduct();
        $product->value = $this->product;
        $product->save();

        $deposit_method = $this->getDepositMethod();
        $deposit_method->value = $this->deposit_method;
        $deposit_method->save();

        $withdrawal_method = $this->getWithdrawalMethod();
        $withdrawal_method->value = $this->withdrawal_method;
        $withdrawal_method->save();
        
        return true;
    }

    public function loadData()
    {
        $product = $this->getProduct();
        $this->product = $product->value;

    }

    public function getProduct()
    {
        $product = OperatorMeta::find()->where(['key' => 'product'])->one();
        if (!$product) {
            $product = new OperatorMeta(['key' => 'product']);
        }
        return $product;
    }

    public function getWithdrawalMethod()
    {
        $model = OperatorMeta::find()->where(['key' => 'withdrawal_method'])->one();
        if (!$model) {
            $model = new OperatorMeta(['key' => 'withdrawal_method']);
        }
        return $model;
    }

    public function getDepositMethod()
    {
        $model = OperatorMeta::find()->where(['key' => 'deposit_method'])->one();
        if (!$model) {
            $model = new OperatorMeta(['key' => 'deposit_method']);
        }
        return $model;
    }
}
