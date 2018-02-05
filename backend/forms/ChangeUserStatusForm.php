<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

class ChangeUserStatusForm extends Model
{
    public $id;

    protected $_user;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateUser'],
        ];
    }

    public function active()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->status = User::STATUS_ACTIVE;
            return $user->save();
        }
        return false;
    }

    public function delete()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->status = User::STATUS_DELETED;
            return $user->save();
        }
        return false;
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->id);
        }

        return $this->_user;
    }

    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, 'User is not exist.');
            }
        }
    }

}
