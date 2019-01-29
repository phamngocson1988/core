<?php
namespace api\forms;

use yii\base\Model;
use api\models\Customer;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;

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
            ['email', 'unique', 'targetClass' => Customer::className(), 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
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
        $user->name = $this->email;
        $user->username = $this->email;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($this->isNeedConfirm()) {
            $user->status = Customer::STATUS_INACTIVE;
        } else {
            $user->status = Customer::STATUS_ACTIVE;
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
}
