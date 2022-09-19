<?php
namespace website\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;

/**
 * ResellerPrice model
 */
class ResellerPrice extends \common\models\ResellerPrice
{
    public static function find()
	{
		return new ResellerPriceQuery(get_called_class());
    }   
}

class ResellerPriceQuery extends ActiveQuery
{
    public function init()
    {
        $now = date('Y-m-d H:i:s');
        $this->andOnCondition(['>', 'invalid_at', $now]);
        parent::init();
    }
}