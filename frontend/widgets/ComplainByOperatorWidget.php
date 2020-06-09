<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\Operator;
use frontend\models\Complain;

class ComplainByOperatorWidget extends Widget
{
    public $limit = 10;

    public function run()
    {
        $stats = Complain::find()
        ->groupBy(["operator_id"])
        ->select(["operator_id", 'COUNT(*) as count_complain'])
        ->orderBy(['count_complain' => SORT_DESC])
        ->asArray()
        ->limit(10)
        ->all();
        $stats = ArrayHelper::map($stats, 'operator_id', 'count_complain');
        $operatorIds = array_keys($stats);
        $operators = Operator::findAll($operatorIds);
        return $this->render('complain_by_operator', [
            'stats' => $stats,
            'operators' => $operators,
        ]);
    }
}