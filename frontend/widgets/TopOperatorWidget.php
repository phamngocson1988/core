<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\Complain;
use frontend\models\OperatorReview;
use frontend\models\Operator;

class TopOperatorWidget extends Widget
{
    public $limit = 10;
    public function run()
    {
        $connection = Yii::$app->db;
        $complainTable = Complain::tableName();
        $reviewTable = OperatorReview::tableName();
        $command = $connection->createCommand("
        	select operator_id, sum(point) as point from 
			(
			select operator_id, count(*) * -1 as point from {$complainTable}
			group by operator_id
			union
			select operator_id, sum(star) as point from {$reviewTable}
			group by operator_id
			) as operator_point
			group by operator_id
			order by point desc
			limit {$this->limit};
		");
		$result = $command->queryAll();
		$operatorIds = ArrayHelper::getColumn($result, 'operator_id');
        $operators = Operator::find()->where(['in', 'id', $operatorIds])->indexBy('id')->all();
        $topOperators = [];
        foreach ($operatorIds as $id) {
        	$topOperators[] = $operators[$id];
        }
        return $this->render('top_operator', [
            'topOperators' => $topOperators,
        ]);
    }
}