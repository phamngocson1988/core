<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;
use backend\models\User;

class ActivateUserForm extends Model
{
    public $id;
	public $password;
    public $activation_key;

    protected $_user;

    const SCENARIO_CHECK_KEY = 'check_key';
    const SCENARIO_CREATE_PASS = 'create_pass';

    public function scenarios()
    {
        return [
            self::SCENARIO_CHECK_KEY => ['id', 'activation_key'],
            self::SCENARIO_CREATE_PASS => ['id', 'activation_key', 'password'],
        ];
    }


	public function rules()
    {
        return [
            [['id', 'activation_key'], 'required'],
            ['id', 'validateUser'],
            ['id', 'validateStatus'],
            ['activation_key', 'trim'],
            ['activation_key', 'validateActivationKey'],

            ['password', 'required', 'on' => self::SCENARIO_CREATE_PASS],
            ['password', 'string', 'min' => 6, 'on' => self::SCENARIO_CREATE_PASS],
            [['password'], function ($attribute, $params) {
                if (preg_match('/\s+/',$this->$attribute)) {
                     $this->addError($attribute, Yii::t('app', 'no_white_space_allowed')); //No white spaces allowed!
                }
            }, 'on' => self::SCENARIO_CREATE_PASS],
        ];
    }

    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'user_not_exist', ['user' => '#' . $this->id]));
                return false;    
            }
        }
    }

    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) return;
            if (!array_key_exists($user->status, User::getUserStatus())) {
                $this->addError($attribute, Yii::t('app', 'cannot_detach_user_status'));
                return false;    
            } elseif ($user->isActive()) {
                $this->addError($attribute, Yii::t('app', 'user_is_activated'));
                return false;    
            } elseif ($user->isDeleted()) {
                $this->addError($attribute, Yii::t('app', 'user_is_deleted'));
                return false;    
            }

        }
    }

    public function validateActivationKey($attribute, $params) 
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) return;
            if ($user->auth_key != $this->$attribute) {
                $this->addError($attribute, Yii::t('app', 'activation_key_is_invalid'));
                return false;    
            }
        }
    }

    /**
     * Main function
     */
    public function activate()
    {
        if (!$this->validate()) {
            return false;
        }
        $user = $this->getUser();
        $user->generateAuthKey();
        $user->setPassword($this->password);
        $user->status = User::STATUS_ACTIVE;
        return $user->save() ? $user : false;
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->id);
        }

        return $this->_user;
    }
}
