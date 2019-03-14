<?php

namespace backend\forms;

use Yii;
use common\models\PricingCoin;

class SetPricingCoinBest extends PricingCoin
{
    public function rules()
    {
        return [
            ['id', 'required'],
        ];
    }

    public function setBest()
    {
        $models = PricingCoin::find()->all();
        foreach ($models as $model) {
            $model->is_best = self::IS_NOT_BEST;
            $model->save();
        }
        $this->is_best = self::IS_BEST;
        return $this->save();
    }
}