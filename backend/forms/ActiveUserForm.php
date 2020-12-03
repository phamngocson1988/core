<?php

namespace backend\forms;

use Yii;
use common\models\User;

class ActiveUserForm extends ActionForm
{
    public $id;

    protected $_user;

    public function rules()
    {
    	return [
    		['id', 'required'],
    		['id', 'validateUser']
    	];
    }

    public function validateUser($attribute, $params = [])
    {
    	$user = $this->getUser();
    	if (!$user) {
    		$this->addError($attribute, Yii::t('app', 'User ID {id} is not exist', ['id' => $this->id]));
    	} else if ($user->isDeleted()) {
    		$this->addError($attribute, Yii::t('app', 'User {user} is not exist', ['user' => $user->username]));
        } else if ($user->isActive()) {
            $this->addError($attribute, Yii::t('app', 'User {user} is active already', ['user' => $user->username]));
    	}
    }

    public function getUser()
    {
    	if (!$this->_user) {
    		$this->_user = User::findOne($this->id);
    	}
    	return $this->_user;
    }

    public function active()
    {
    	if (!$this->validate()) return false;
    	$user = $this->getUser();
    	$user->status = User::STATUS_ACTIVE;
    	return $user->save();
    }
}
