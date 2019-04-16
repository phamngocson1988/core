<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\models\User;
use frontend\models\Game;
use yii\helpers\ArrayHelper;
use frontend\components\notifications\AccountNotification;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $name;
    public $country_code;
    public $phone;
    public $birthday;
    public $favorite;
    public $is_reseller;
    public $invite_code;
    public $verifyCode;
    

    /**
     * @param boolean $is_active
     * If false, the customer will be actived after signup
     * If true, the customer will receive an activation email
     */
    protected $need_confirm = false;

    /**
     * @inheritdoc
     */
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

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'max' => 20],

            [['favorite', 'country_code', 'birthday', 'invite_code'], 'trim'],
            ['is_reseller', 'default', 'value' => User::IS_NOT_RESELLER],
            ['is_reseller', 'in', 'range' => array_keys(User::getResellerStatus())],

            ['verifyCode', 'required'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new User();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->country_code = $this->country_code;
        $user->phone = $this->phone;
        $user->favorite = $this->favorite;
        $user->birthday = $this->birthday;
        $user->affiliate_code = Yii::$app->security->generateRandomString(6);
        $user->is_reseller = $this->is_reseller;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($this->invite_code) {
            $affiliateUser = User::findOne(['affiliate_code' => $this->invite_code]);
            $user->invited_by = $affiliateUser->id;
        }

        if ($this->isNeedConfirm()) {
            $user->status = User::STATUS_INACTIVE;
        } else {
            $user->status = User::STATUS_ACTIVE;
        }
        return $user->save() ? $user : null;
    }

    /**
     * Set need_confirm flag
     * 
     * @param boolean $needConfirm
     */
    public function setNeedConfirm($needConfirm)
    {
        $this->need_confirm = (boolean)$needConfirm;
    }

    /**
     * Get need_confirm flag
     * 
     * @return boolean
     */
    public function isNeedConfirm()
    {
        return (boolean)$this->need_confirm;
    }

    public function fetchGames()
    {
        $games = Game::find()->all();
        return ArrayHelper::map($games, 'id', 'title');
    }
}
