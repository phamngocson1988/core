<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use frontend\models\Complain;
use frontend\models\Operator;

class ComplainStatByOperatorWidget extends Widget
{
    public $operator_id;
    public $operator;

    public function run()
    {
        if (!$this->operator_id) return '';

        $operator = $this->getOperator();
        $records = $command = Complain::find()
        ->where(['operator_id' => $this->operator_id])
        ->groupBy(['status'])
        ->select(['status', 'COUNT(*) as count'])
        ->asArray()
        ->all();
        ;
        $stat = ArrayHelper::map($records, 'status', 'count');
        $open = ArrayHelper::getValue($stat, Complain::STATUS_OPEN, 0);
        $resolve = ArrayHelper::getValue($stat, Complain::STATUS_RESOLVE, 0);
        $reject = ArrayHelper::getValue($stat, Complain::STATUS_REJECT, 0);
        $total = array_sum($stat);

        return $this->render('complain_stat_by_operator', [
            'total' => $total,
            'open' => $open,
            'resolve' => $resolve,
            'reject' => $reject,
            'operator' => $operator
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