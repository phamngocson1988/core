<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;
use common\models\Complain;

class OperatorComplainBehavior extends AttributeBehavior
{
    public function totalComplain($status = null) 
    {
        $owner = $this->owner; // Operator
        $command = Complain::find()->where([
            'operator_id' => $owner->id
        ]);
        if ($status) {
            $command->andWhere(['status' => $status]);
        }
        return $command->count();
    }

    public function averageRespondTime() 
    {
        $owner = $this->owner; // Operator
        $avg = Complain::find()
        ->where(['operator_id' => $owner->id])
        ->andWhere(['IS NOT', 'first_reply_at', NULL])
        ->select(['AVG(TIMESTAMPDIFF(HOUR, created_at, first_reply_at)) AS hours'])
        ->asArray()
        ->all();
        if (count($avg)) {
            $row = reset($avg);
            return number_format(ArrayHelper::getValue($row, 'hours', 0));
        }
        return 0;
    }

}
