<?php
namespace website\components\payment\clients;

use Yii;

class OfflinePayment extends \website\models\Paygate
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