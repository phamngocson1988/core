<?php
namespace frontend\models;

use Yii;
use yii\db\ActiveQuery;

class PricingCoin extends \common\models\PricingCoin
{
	public static function find()
	{
		return new PricingCoinQuery(get_called_class());
	}
}

class PricingCoinQuery extends ActiveQuery
{
    public function init()
    {
        $this->andOnCondition(['status' => PricingCoin::STATUS_VISIBLE]);
        parent::init();
    }
}