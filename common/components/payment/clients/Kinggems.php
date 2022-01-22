<?php
namespace common\components\payment\clients;

use Yii;
// use yii\base\Model;

class Kinggems extends \api\models\Paygate
{
	public $content = '';
	public $exchange_rate = 1;
	public $name = 'Kinggems';
	
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

	public function isOnline()
	{
		return true;
	}
}