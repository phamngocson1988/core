<?php
namespace frontend\components\payment\clients;

use Yii;
// use yii\base\Model;

class Kinggems extends \frontend\models\Paygate
{
	public function getIdentifier()
	{
		return 'kinggems';
	}

	public function getPaymentType()
	{
		return 'online';
	}

	public function getCurrency()
	{
		return 'USD';
	}

	public function getFee($total = 0)
	{
		return 0;
	}
}