<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\User;
use common\models\OperatorStaff;

class OperatorStaffBehavior extends AttributeBehavior
{
    public function fetchStaff($role)
    {
        $owner = $this->owner; // Operator
        $staffTable = OperatorStaff::tableName();
        $userTable = User::tableName();
        return User::find()
        ->innerJoin($staffTable, "{$staffTable}.user_id = {$userTable}.id")
        ->where([
            "{$staffTable}.role" => $role,
            "{$staffTable}.operator_id" => $owner->id,
        ])
        ->all();
    }
}
