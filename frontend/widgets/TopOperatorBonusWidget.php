<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;
use frontend\models\Bonus;

class TopOperatorBonusWidget extends Widget
{
    public $limit = 10;
    public $operator;
    public $operator_id;

    public function run()
    {
    	$operator = $this->getOperator();
    	if (!$operator) return '';

        $bonuses = Bonus::find()->where(['operator_id' => $operator->id])->limit($this->limit)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('top_operator_bonus', [
            'operator' => $operator,
            'bonuses' => $bonuses,
        ]);
    }

    public function getOperator()
    {
    	if (!$this->operator) {
    		$this->operator = Operator::findOne($this->operator_id);
    	}
    	return $this->operator;
    }
}