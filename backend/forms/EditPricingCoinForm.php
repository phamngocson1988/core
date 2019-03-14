<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\PricingCoin;

/**
 * CreatePricingCoinForm
 */
class EditPricingCoinForm extends PricingCoin
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['title', 'description'], 'trim'],
            [['id', 'title', 'num_of_coin', 'amount'], 'required'],

            ['status', 'in', 'range' => array_keys(PricingCoin::getStatusList())],
        ];
    }
}
