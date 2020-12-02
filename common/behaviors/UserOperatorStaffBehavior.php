<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\OperatorStaff;

class UserOperatorStaffBehavior extends AttributeBehavior
{
    public function isOperatorStaffOf($operatorId, $role = null) 
    {
        $owner = $this->owner; // User
        $condition = [
            'user_id' => $owner->id,
            'operator_id' => $operatorId,
            'role' => $role,
        ];
        $condition = array_filter($condition);
        return OperatorStaff::find()->where($condition)->exists();
    }

    public function isNotOperatorStaffOf($operatorId, $role = null) 
    {
        $owner = $this->owner; // User
        $condition = [
            'user_id' => $owner->id,
            'operator_id' => $operatorId,
            'role' => $role,
        ];
        $condition = array_filter($condition);
        return !OperatorStaff::find()->where($condition)->exists();
    }

    public function isOperatorStaff()
    {
    	$owner = $this->owner; // User
        return OperatorStaff::find()->where([
            'user_id' => $owner->id,
        ])->exists();
    }

}
