<?php
namespace website\libraries\payment;

use Yii;

return [
    'mode' => Yii::$app->params['payment']['mode'],
    PaymentConstants::GATEWAY_COIN_PAID => Yii::$app->params['payment'][PaymentConstants::GATEWAY_COIN_PAID],
    // PaymentConstants::GATEWAY_ALI_PAY => Yii::$app->params['payment'][PaymentConstants::GATEWAY_ALI_PAY],
    // PaymentConstants::GATEWAY_WE_CHAT => Yii::$app->params['payment'][PaymentConstants::GATEWAY_WE_CHAT],
    // PaymentConstants::GATEWAY_SKRILL => Yii::$app->params['payment'][PaymentConstants::GATEWAY_SKRILL],
    PaymentConstants::GATEWAY_COIN_BASE => Yii::$app->params['payment'][PaymentConstants::GATEWAY_COIN_BASE],
];