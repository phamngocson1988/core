<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\User;
use website\components\verification\email\Email;
use common\models\UserDevices;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $securityCode;
    public $browser_token;
    public $rememberMe = false;

    const SCENARIO_LOGIN = 'SCENARIO_LOGIN';
    const SCENARIO_VERIFY = 'SCENARIO_VERIFY';
 
    private $_user;
    private $_device;

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
            ['browser_token', 'trim'],

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
            $user = $this->getUser();
            switch ($this->scenario) {
                case self::SCENARIO_LOGIN:
                    $checkDeviceCommand = $this->getDevice();
                    if ($checkDeviceCommand) {
                        $this->updateDevice();
                        return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
                    } else {
                        $verifyService = new Email();
                        return $verifyService->send($user->email, 'Kinggems.us: Your verification code is {pin}.');
                    }
                case self::SCENARIO_VERIFY:
                    if ($this->rememberMe) {
                        $this->updateDevice();
                    }
                    return Yii::$app->user->login($user, $this->rememberMe ? 3600 * 24 * 30 : 0);
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

    protected function getDevice()
    {
        if (!$this->_device) {
            $user = $this->getUser();
            if ($user) {
                $this->_device = UserDevices::find()->where([
                    'user_id' => $user->id,
                    'browser_token' => $this->browser_token,
                ])->one();
            }
        }
        return $this->_device;
    }

    protected function updateDevice()
    {
        $device = $this->getDevice();
        if (!$device) {
            $user = $this->getUser();
            $device = new UserDevices();
            $device->user_id = $user->id;
            $device->browser_token = $this->browser_token;
        }

        $user_ip = $_SERVER['REMOTE_ADDR'];
        $geo = unserialize(file_get_contents("http://www.geoplugin.net/php.gp?ip=$user_ip"));
        $country = $geo["geoplugin_countryName"];
        $city = $geo["geoplugin_city"];
        $last_login_location = sprintf("%s, %s", $country, $city);

        $device->last_login_location = $last_login_location;
        $device->browser_info = $_SERVER['HTTP_USER_AGENT'];
        $device->save();
    }
}
