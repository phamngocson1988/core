<?php
namespace common\behaviors;

use Yii;
use yii\helpers\ArrayHelper;
use yii\behaviors\AttributeBehavior;
use common\models\User;

class OperatorRoleBehavior extends AttributeBehavior
{
    public function listUserByRole($role)
    {
        $owner = $this->owner; // User
        $auth = Yii::$app->authManager;
        $userTable = User::tableName();
        $roleTable = $auth->assignmentTable;
        return User::find()
        ->innerJoin($roleTable, "{$roleTable}.user_id = {$userTable}.id")
        ->where([
            "{$roleTable}.item_name" => $role,
            "{$userTable}.operator_id" => $owner->id,
        ])
        ->all();
    }
}
