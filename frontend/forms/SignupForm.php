<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\User;
use frontend\models\Game;
use yii\helpers\ArrayHelper;
use frontend\events\AfterSignupEvent;
use common\models\Country;
/**
 * Signup form
 */
class SignupForm extends Model
{
    const EVENT_AFTER_SIGNUP = 'EVENT_AFTER_SIGNUP';
    
    public $email;
    public $password;
    public $username;
    public $name;

    // In case refer
    public $refer;

    // In case affiliate
    public $affiliate;

    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => User::className(), 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            [['refer', 'affiliate'], 'safe'],

            [['username', 'name'], 'trim']
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
        $user->email = $this->email;
        $user->username = $this->username ? $this->username : $this->email;
        $user->name = $this->name ? $this->name : $this->email;
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
}
