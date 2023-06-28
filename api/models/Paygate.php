<?php
namespace api\models;

use Yii;

class Paygate extends \common\models\Paygate
{
    public function fields()
    {
        return [
            'id',
            'name',
            'identifier',
            'paygate_type',
            // 'content',
            'logo' => function($model) {
                return $model->getImageUrl();
            },
            'transfer_fee',
            'transfer_fee_type',
            'currency',
            'exchange_rate' => function($model) {
                $currency = \common\models\CurrencySetting::findOne(['code' => $model->currency]);
                if ($currency) return $currency->exchange_rate;
                return null;
            },
        ];
    }
}