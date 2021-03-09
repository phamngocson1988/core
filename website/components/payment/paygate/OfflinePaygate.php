<?php
namespace website\components\payment\paygate;

use Yii;
use website\models\UserWallet;
use yii\helpers\Url;

class OfflinePaygate
{
    public $config;
    public function _construct($config)
    {
        $this->config = $config;
    }    

    public function createCharge($order, $user = null) 
    {
        return Url::to(['cart/thankyou', 'id' => $order->id], true);
    }
}