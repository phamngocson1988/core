<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\base\Exception;
use frontend\components\payment\PaymentGateway;

class OfflinePayment extends PaymentGateway
{
    public $identifier = 'offline';
    public $type = 'offline';

    protected function loadConfig()
    {
        $settings = Yii::$app->settings;
    }

    protected function loadData()
    {
    }

    protected function sendRequest()
    {
        return $this->doSuccess();
    }
    
    protected function verify($response)
    {
        
    }

    public function cancelPayment()
    {
        return true;
    }

    public function doSuccess()
    {
        $refId = $this->getReferenceId();
        return $this->redirect($this->getSuccessUrl(['ref' => $refId]));
    }

    public function doError()
    {
        return $this->redirect($this->getErrorUrl());
    }

}