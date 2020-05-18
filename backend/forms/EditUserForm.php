<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\User;
use common\models\Country;
use yii\helpers\ArrayHelper;

class EditUserForm extends Model
{
    public $id;
	public $username;
    public $email;
    public $password;
	public $firstname;
    public $lastname;
    public $country;
    public $gender;

    protected $_user;

	/**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateUser'],

            ['password', 'trim'],
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

    public function attributeLabels()
    {
        return [
            'username' => Yii::t('app', 'username'),
            'email' => Yii::t('app', 'email'),
            'password' => Yii::t('app', 'password'),
            'firstname' => Yii::t('app', 'firstname'),
            'lastname' => Yii::t('app', 'lastname'),
            'country' => Yii::t('app', 'country'),
            'gender' => Yii::t('app', 'gender'),
        ];
    }

    public function validateUser($attribute, $params = []) 
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'user_is_not_exist'));
        }
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->id);
        }
        return $this->_user;
    }

    public function update()
    {
        $user = $this->getUser();
        if ($this->password && !$user->validatePassword($this->password)) {
            $user->setPassword($this->password);
            $user->generateAuthKey();
        }
        $user->firstname = $this->firstname;        
        $user->lastname = $this->lastname;        
        $user->country = $this->country;        
        $user->gender = $this->gender;        
        
        return $user->save() ? $user : null;
    }

    public function fetchCountry()
    {
        $countries = Country::fetchAll();
        return ArrayHelper::map($countries, 'country_code', 'country_name');
    }

    public function fetchGender()
    {
        return User::getUserGender();
    }

    public function loadData()
    {
        $user = $this->getUser();
        $this->username = $user->username;
        $this->email = $user->email;
        $this->firstname = $user->firstname;        
        $this->lastname = $user->lastname;        
        $this->country = $user->country;        
        $this->gender = $user->gender;        
    }
}
