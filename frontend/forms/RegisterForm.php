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
    public $currency;
    public $captcha;

    // In case refer
    public $refer;

    // In case affiliate
    public $affiliate;

    // Saler code
    public $saler_code;

    public function scenarios()
    {
        return [
            self::SCENARIO_INFORMATION => ['firstname', 'lastname', 'username', 'password', 'repassword', 'email', 
            'birth_date', 'birth_month', 'birth_year', 'country_code', 'phone', 'currency', 'captcha', 'refer', 'affiliate', 'saler_code'],
            self::SCENARIO_VALIDATE => ['firstname', 'lastname', 'username', 'password', 'repassword', 'email', 
            'birth_date', 'birth_month', 'birth_year', 'country_code', 'phone', 'currency', 'captcha', 'refer', 'affiliate', 'saler_code'],
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

            ['phone', 'trim'],
            [['country_code', 'phone'], 'safe'],
            // ['phone', 'unique', 'targetClass' => User::className(), 'message' => 'This phone has already been taken.'],

            ['captcha', 'required'],
            ['captcha', 'captcha', 'message' => 'Captcha is not match'],

            // Saler
            ['saler_code', 'trim'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
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
        $user->status = User::STATUS_INACTIVE;
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
        if (!$this->verifier) throw \Exception('You have not added verifier service');
        $service = $this->verifier; //sms
        $service->compose()
            ->setTo('+84907877310')
            ->setMessage("Kinggems: send test sms from trail account")
            ->send();
        print_r($result);
        die;
    }

}
