<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
/**
 * AssignSalerToUserForm
 */
class AssignSalerToUserForm extends Model
{
    public $saler_id;
    public $user_id;
    public $force_update = false;

    protected $allow_roles = ['saler', 'saler_manager'];
    private $_user;

    public function rules()
    {
        return [
            [['saler_id', 'user_id'], 'required'],
            ['saler_id', 'validateSaler'],
            ['user_id', 'validateUser'],
            ['force_update', 'safe']
        ];
    }

    public function run()
    {
        if (!$this->validate()) return false;
        $user = $this->getUser();
        $user->setScenario(User::SCENARIO_UPDATE_SALER_ID);
        $user->saler_id = $this->saler_id;
        $user->save();
        return true;
    }

    public function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function validateUser($attribute, $params)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->addError($attribute, 'User is not exist');
        }
        if ($user->isReseller()) {
        return $this->addError($attribute, 'Cannot assign AM to reseller.');
        }
        if ($user->saler_id && !$this->force_update) {
            return $this->addError($attribute, "The user was taken by another AM member");
        }
    }

    public function validateSaler($attribute, $params)
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRolesByUser($this->saler_id);
        $roleNames = array_keys($roles);
        $intersect = array_intersect($roleNames, $this->allow_roles);
        if (!count($intersect)) {
            return $this->addError($attribute, "Assignee is not in AM team");
        }
    }
}
