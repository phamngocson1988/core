<?php
namespace frontend\components\payment\clients;

use Yii;

class OfflinePayment extends \frontend\models\Paygate
{
	public function getIdentifier()
	{
		return $this->identifier;
	}

	public function getPaymentType()
	{
		return $this->paygate_type;
	}

	public function getCurrency()
	{
		return $this->currency;
	}
}