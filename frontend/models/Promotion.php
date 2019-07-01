<?php
namespace frontend\models;

use Yii;
use yii\helpers\ArrayHelper;
use yii\db\ActiveQuery;

/**
 * Promotion model
 */
class Promotion extends \common\models\Promotion
{
    public static function find()
	{
		return new PromotionQuery(get_called_class());
    }
    
    public function isValid($params) 
    {
        $rule = $this->getRule();
        if (!$rule) return true;
        return $rule->isValid($params);
    }
}

class PromotionQuery extends ActiveQuery
{
    public function init()
    {
        $now = date('Y-m-d');
        $this->where([
            'status' => Promotion::STATUS_VISIBLE,
            'is_valid' => Promotion::IS_VALID,
        ]);
        $this->andWhere(['OR', 
            ['<=', 'from_date', $now],
            ['from_date' => null]
        ]);
        $this->andWhere(['OR', 
            ['>=', 'to_date', $now],
            ['to_date' => null]
        ]);
        parent::init();
    }
}