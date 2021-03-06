<?php

namespace backend\forms;

use Yii;
use common\forms\SignupForm as BaseSignupForm;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;

class SignupForm extends BaseSignupForm
{
	public $role;

	public function rules()
    {
        $rules = parent::rules();
        $rules[] = ['role', 'trim'];
        return $rules;
    }

	public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $names = ArrayHelper::map($roles, 'name', 'description');
        return $names;
    }
	
	public function signup()
	{
		$user = parent::signup();
		if ($this->role && ($user instanceof \common\models\User)) {
			$form = new AssignRoleForm(['user_id' => $user->id, 'role' => $this->role]);
			$form->save();
		}
		return $user;
	}
}
