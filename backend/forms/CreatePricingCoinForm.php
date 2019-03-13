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
            [['title', 'num_of_coin', 'amount_usd'], 'required'],

            ['status', 'in', 'range' => array_keys(PricingCoin::getStatusList())],
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
