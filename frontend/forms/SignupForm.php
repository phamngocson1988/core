<?php
namespace frontend\forms;

use yii\base\Model;
use common\models\Customer;
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
    public $phone;
    public $favorite;

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
            ['email', 'unique', 'targetClass' => '\common\models\Customer', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'max' => 20],

            ['favorite', 'safe'],
        ];
    }

    /**
     * Signs user up.
     *
     * @return Customer|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        
        $user = new Customer();
        $user->username = $this->email;
        $user->email = $this->email;
        $user->name = $this->name;
        $user->phone = $this->phone;
        $user->favorite = $this->favorite;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($this->isNeedConfirm()) {
            $user->status = Customer::STATUS_INACTIVE;
        } else {
            $user->status = Customer::STATUS_ACTIVE;
        }
        
        return $user->save() ? $user : null;

        // AccountNotification::create(AccountNotification::KEY_NEW_ACCOUNT, ['user' => $user])->send();
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
