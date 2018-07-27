<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

/**
 * FetchUserByRoleForm
 */
class FetchUserByRoleForm extends Model
{
    public $role;
    private $_command;

    public function rules()
    {
        return [
            [['role'], 'trim'],
        ];
    }

    public function fetch()
    {
        $userIds = Yii::$app->authManager->getUserIdsByRole($this->role);
        return User::findAll($userIds);
    }
}
