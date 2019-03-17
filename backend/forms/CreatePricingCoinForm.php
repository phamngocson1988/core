<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\PricingCoin;

/**
 * CreatePricingCoinForm
 */
class CreatePricingCoinForm extends PricingCoin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'trim'],
            [['title', 'num_of_coin', 'amount'], 'required'],

            ['status', 'in', 'range' => array_keys(PricingCoin::getStatusList())],
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'description' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'num_of_coin' => Yii::t('app', 'num_of_coin'),
            'unit_name' => Yii::t('app', 'unit_name'),
            'amount' => Yii::t('app', 'amount'),
        ];
    }

    /**
     * Signs user up.
     *
     * @return PricingCoin|null the saved model or null if saving fails
     */
    public function create()
    {
        if (!$this->validate()) {
            return null;
        }
        
        if ($this->save()) {
            return $this;
        }
        return false;
    }
}
