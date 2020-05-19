<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = false;
 
    private $_user;

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'validateStatus'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],
        ];
    }

    public function validateStatus($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if ($user) {
                if ($user->status == User::STATUS_INACTIVE) {
                    $this->addError('username', Yii::t('frontend', 'customer_is_not_active'));
                    return false;    
                } elseif ($user->status == User::STATUS_DELETED) {
                    $this->addError('username', Yii::t('frontend', 'customer_is_deleted'));
                    return false;    
                }
            }
        }
    }

    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Incorrect username or password.');
            }
        }
    }

    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByUsername($this->username);
        }

        return $this->_user;
    }
}
