<?php 
namespace common\models;

use Yii;
use yii\db\ActiveQuery;

interface PaymentCommitmentInterface
{
    public function getObject();
    public function getObjectKey();
}