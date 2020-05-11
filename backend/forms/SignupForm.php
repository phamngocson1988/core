<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;

class SignupForm extends Model
{
	public $username;
    public $email;
    public $password;
	public $firstname;
    public $lastname;
    public $country;
    public $gender;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => User::className(), 'message' => Yii::t('app', 'validate_username_unique')],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => Yii::t('app', 'validate_email_unique')],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['firstname', 'trim'],
            ['firstname', 'string', 'max' => 255],

            ['lastname', 'trim'],
            ['lastname', 'string', 'max' => 255],

            ['country', 'trim'],
            ['country', 'string', 'max' => 64],

            ['gender', 'trim'],
            ['gender', 'string'],
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
        $user->username = $this->username;
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        $user->firstname = $this->firstname;        
        $user->lastname = $this->lastname;        
        $user->country = $this->country;        
        $user->gender = $this->gender;        
        
        return $user->save() ? $user : null;
    }
}
