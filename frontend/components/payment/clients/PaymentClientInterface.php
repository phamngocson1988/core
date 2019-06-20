<?php
namespace frontend\components\payment\clients;

use Yii;
use yii\base\Model;

interface PaymentClientInterface extends Model
{
    // public function loadConfig();
    // public function loadData();
    public function request();
    public function confirm();
}