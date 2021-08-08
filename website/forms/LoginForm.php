<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;
use website\components\verification\email\Email;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $securityCode;
    public $rememberMe = false;

    const SCENARIO_LOGIN = 'SCENARIO_LOGIN';
    const SCENARIO_VERIFY = 'SCENARIO_VERIFY';
 
    private $_user;

    public function scenarios()
    {
        return [
            self::SCENARIO_LOGIN => ['username', 'password', 'rememberMe'],
            self::SCENARIO_VERIFY => ['username', 'password', 'securityCode', 'rememberMe'],
        ];
    }

    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['username', 'validateStatus'],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword'],

            ['securityCode', 'trim', 'on' => self::SCENARIO_VERIFY],
            ['securityCode', 'required', 'on' => self::SCENARIO_VERIFY],
            ['securityCode', 'validateCode', 'on' => self::SCENARIO_VERIFY]
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

    public function validateCode($attribute, $params) 
    {
        /**
         * TODO
         * Get code from somewhere
         * Compare the code with what user input
         *
         * @return void
         */
        $verifyService = new Email();
        if (!$verifyService->verify($this->$attribute)) {
            return $this->addError($attribute, 'Verification code is not valid');
        }
    }


    public function login()
    {
        if ($this->validate()) {
            switch ($this->scenario) {
                case self::SCENARIO_LOGIN:
                    /**
                     * TODO:
                     * Create code, save to somewhere
                     * Send code to email
                     */
                    $verifyService = new Email();
                    $user = $this->getUser();
                    return $verifyService->send($user->email, 'Kinggems.us: Your verification code is {pin}');
                case self::SCENARIO_VERIFY:
                    return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
                default: 
                    return false;
            }
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
