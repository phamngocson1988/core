<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;
use common\models\User;
use common\models\Country;

class EditUserForm extends Model
{
    public $id;
	public $name;
    public $username;
    public $password;
    public $email;
    public $country_code;
    public $phone;
    public $address;
    public $birthday;
    public $status;

    protected $_user;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'uniqueEmail'],

            ['password', 'trim'],
            ['password', 'string', 'min' => 6],

            ['status', 'in', 'range' => array_keys(User::getUserStatus())],
            [['phone', 'address', 'birthday'], 'trim'],
            ['country_code', 'required'],
            // ['phone', 'match', 'pattern' => '/^[0-9]+((\.|\s)?[0-9]+)*$/i'],
        ];
    }

    public function uniqueEmail($attribute, $params = []) 
    {
        $user = $this->getUser();
        if ($user->email != $this->email) {
            $findingUser = User::find()->where(['email' => $this->email])->one();
            if ($findingUser) {
                $this->addError($attribute, sprintf('Hộp thư điện tử bị trùng %s (#%s)', $findingUser->username, $findingUser->id));
            }
        }
    }

	public function getRoles()
    {
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $names = ArrayHelper::map($roles, 'name', 'description');
        return $names;
    }
	
	public function edit()
	{
		$connection = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			if (!$this->validate()) {
	            return false;
	        }
	        
	        $user = $this->getUser();
            $user->name = $this->name;    
            $user->email = $this->email;    
            $user->country_code = $this->country_code;
            $user->phone = $this->phone;
            $user->address = $this->address;
            $user->birthday = $this->birthday;
            $user->status = $this->status;
            if ($this->password) {
                $user->setPassword($this->password);
                $user->generateAuthKey();
            }
	        $user->save();
			$transaction->commit();
            Yii::$app->syslog->log('edit_user', 'edit user', $user);
			return $user;
		} catch (\Exception $e) {
			$transaction->rollBack();
			$this->addError('username', $e->getMessage());
			return false;
		}
	}

    public function getUserStatus()
    {
        return User::getUserStatus();
    }

    public function loadData()
    {
        $user = $this->getUser();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->country_code = $user->country_code;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->birthday = $user->birthday;
        $this->status = $user->status;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->id);
        }
        return $this->_user;
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
}
