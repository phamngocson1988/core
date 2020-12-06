<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\OperatorReview;
use frontend\models\Operator;

class ReviewStatByOperatorWidget extends Widget
{
    public $operator_id;
    public $operator;

    public function run()
    {
        if (!$this->operator_id) return '';

        $operator = $this->getOperator();
        $command = OperatorReview::find()->where(['operator_id' => $this->operator_id]);
        $unreplyCommand = clone $command;
        $replyCommand = clone $command;
        $total = $command->count();
        $unreply = $unreplyCommand->andWhere(['IS', 'replied_by', null])->count();
        $reply = $replyCommand->andWhere(['IS NOT', 'replied_by', null])->count();
        return $this->render('review_stat_by_operator', [
            'total' => $total,
            'unreply' => $unreply,
            'reply' => $reply,
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