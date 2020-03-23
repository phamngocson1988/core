<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;

class EditUserForm extends Model
{
    public $id;
	public $name;
    public $username;
    public $password;
    public $email;
    public $phone;
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

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'string', 'min' => 2, 'max' => 255],
            ['username', 'uniqueUsername'],

            ['password', 'trim'],
            ['password', 'string', 'min' => 6],

            ['status', 'in', 'range' => array_keys(User::getUserStatus())],
            [['phone'], 'trim'],
        ];
    }

    public function uniqueUsername($attribute, $params = []) 
    {
        $user = $this->getUser();
        if ($user->username == $this->username) return;
        if (User::find()->where(['username' => $this->username])->count() > 0) {
            $this->addError($attribute, 'Tên đăng nhập bị trùng');
        }
    }

    public function uniqueEmail($attribute, $params = []) 
    {
        $user = $this->getUser();
        if ($user->email == $this->email) return;
        if (User::find()->where(['email' => $this->email])->count() > 0) {
            $this->addError($attribute, 'Hộp thư điện tử bị trùng');
        }
    }

	public function edit()
	{
		$connection = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
	        $user = $this->getUser();
            $user->name = $this->name;    
            $user->username = $this->username;    
            $user->email = $this->email;    
            $user->phone = $this->phone;
            $user->status = $this->status;
            if ($this->password) {
                $user->setPassword($this->password);
                $user->generateAuthKey();
            }
	        $user->save();
			$transaction->commit();
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

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->id);
        }
        return $this->_user;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $user = $this->getUser();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->status = $user->status;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Tên nhân viên',
            'username' => 'Tên đăng nhập',
            'email' => 'Thư điện tử',
            'phone' => 'Số điện thoại',
            'password' => 'Mật khẩu',
            'status' => 'Trạng thái',
        ];
    }
}
