<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Bonus;

class OperatorBonusBehavior extends AttributeBehavior
{
    public function countBonus()
    {
        $owner = $this->owner; // Operator
    	return Bonus::find()->where(['operator_id' => $owner->id])->count();
    }
}
