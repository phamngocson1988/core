<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\User;

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
            if ($user->save()) {
                Yii::$app->syslog->log('active_user', 'active user', $user);
                return true;
            }
        }
        return false;
    }

    public function delete()
    {
        if ($this->validate()) {
            $user = $this->getUser();
            $user->status = User::STATUS_DELETED;
            if ($user->save()) {
                Yii::$app->syslog->log('delete_user', 'delete user', $user);
                return true;
            }
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
                $this->addError($attribute, Yii::t('app', 'user_not_exist'));
            }
        }
    }

}
