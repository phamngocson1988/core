<?php
namespace website\components\payment\clients;

use Yii;
use yii\base\Model;

interface PaymentClientInterface extends Model
{
    public function request();
    public function confirm();
}