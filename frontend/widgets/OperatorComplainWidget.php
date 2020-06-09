<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;
use frontend\models\Complain;

class OperatorComplainWidget extends Widget
{
    public $limit = 4;
    public $operator;
    public $operator_id;

    public function run()
    {
    	$operator = $this->getOperator();
    	if (!$operator) return '';

        $total = $operator->totalComplain();
        if (!$total) return '';

        $totalResolve = $operator->totalComplain(Complain::STATUS_RESOLVE);
        $percent = number_format(($totalResolve / $total) * 100, 1);
        $avgResponseTime = $operator->averageRespondTime();

        $complains = Complain::find()->where(['operator_id' => $operator->id])->limit($this->limit)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('operator_complain', [
            'operator' => $operator,
            'complains' => $complains,
            'total' => $total,
            'totalResolve' => $totalResolve,
            'percent' => $percent,
            'avgResponseTime' => $avgResponseTime,
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