<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Game;
use yii\helpers\ArrayHelper;
use frontend\events\AfterSignupEvent;
use common\models\Country;

class RegisterForm extends Model
{
    const SCENARIO_INFORMATION = 'information';
    const SCENARIO_VALIDATE = 'validate';

    const EVENT_AFTER_SIGNUP = 'EVENT_AFTER_SIGNUP';
    
    protected $verifier;

    public $firstname;
    public $lastname;
    public $username;
    public $password;
    public $repassword;
    public $email;
    public $birth_date;
    public $birth_month;
    public $birth_year;
    public $country_code;
    public $phone;
    public $captcha;

    // In case refer
    public $refer;

    // In case affiliate
    public $affiliate;

    // Saler code
    public $saler_code;

    // Verify code
    public $digit_1;
    public $digit_2;
    public $digit_3;
    public $digit_4;

    public function scenarios()
    {
        return [
            self::SCENARIO_VALIDATE => ['firstname', 'lastname', 'username', 'password', 'repassword', 'email', 
            'birth_date', 'birth_month', 'birth_year', 'country_code', 'phone', 'captcha'],
            self::SCENARIO_INFORMATION => ['firstname', 'lastname', 'username', 'password', 'repassword', 'email', 
            'birth_date', 'birth_month', 'birth_year', 'country_code', 'digit_1', 'digit_2', 'digit_3', 'digit_4'],
            
        ];
    }

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'max' => 255],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => 'This username has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['repassword', 'compare', 'compareAttribute'=>'password', 'message'=>"Passwords don't match"],        

            [['firstname', 'lastname'], 'trim'],
            [['firstname', 'lastname'], 'required'],
            [['firstname', 'lastname'], 'string', 'max' => 255],

            [['birth_date', 'birth_month', 'birth_year'], 'required'],
            [['birth_date', 'birth_month', 'birth_year'], 'number'],

            [['country_code', 'phone'], 'trim', 'on' => self::SCENARIO_VALIDATE],
            ['phone', 'unique', 
            'targetClass' => User::className(), 
            'message' => 'This phone has already been taken.', 
            'on' => self::SCENARIO_VALIDATE],

            ['captcha', 'required', 'on' => self::SCENARIO_VALIDATE],
            ['captcha', 'captcha', 'message' => 'Captcha is not match', 'on' => self::SCENARIO_VALIDATE],
        ];
    }

    public function signup()
    {
        if (!$this->validate())  return null;
        $user = new User();
        $user->username = $this->username;
        $user->email = $this->email;
        $user->name = sprintf("%s %s", $this->firstname, $this->lastname);
        $user->country_code = $this->country_code;
        $user->phone = $this->phone;
        $user->birthday = sprintf("%s-%s-%s", $this->birth_year, $this->birth_month, $this->birth_date);
        $user->refer_code = Yii::$app->security->generateRandomString(6);
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->status = User::STATUS_ACTIVE;
        if ($user->save()) {
            $event = new AfterSignupEvent();
            $event->user = $user;
            $this->trigger(self::EVENT_AFTER_SIGNUP, $event);
            return $user;
        }
        return null;
    }

    public function listCountries()
    {
        return ArrayHelper::map(Country::fetchAll(), 'country_code', 'country_name');
    }

    public function listCountryAttributes()
    {
        $attrs = [];
        foreach (Country::fetchAll() as $country) {
            $attrs[$country->country_code] = ['data-dialling' => $country->dialling_code];
        }
        return $attrs;
    }

    public function setVerifier($verifier) 
    {
        $this->verifier = $verifier;
    }

    public function sendVerification()
    {
        $content = "Your activation code is: {pin}";
        return $this->verifier->send($this->phone, $content);
    }

    public function verify()
    {
        $pin = sprintf("%s%s%s%s", $this->digit_1, $this->digit_2, $this->digit_3, $this->digit_4);
        return $this->verifier->verify($pin);
    }
}
