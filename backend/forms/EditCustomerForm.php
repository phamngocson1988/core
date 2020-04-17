<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\User;

class EditCustomerForm extends Model
{
    public $id;
	public $name;
    public $short_name;
    public $phone;
    public $address;
    public $email;
    public $manager_id;

    protected $_customer;
    protected $_manager;

	public function rules()
    {
        return [
            ['id', 'required'],
            
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],


            ['short_name', 'trim'],
            ['short_name', 'string', 'min' => 2, 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 2, 'max' => 16],
            ['phone', 'uniquePhone'],

            ['email', 'trim'],
            ['email', 'email', 'message' => 'Không đúng định dạng email'],
            ['email', 'string', 'max' => 255, 'message' => 'Thông tin hộp thư không được quá 255 ký tự'],

            ['address', 'string', 'max' => 255, 'message' => 'Thông tin địa chỉ không được quá 255 ký tự'],
            ['manager_id', 'validateManager']
        ];
    }

    public function uniquePhone($attribute, $params = []) 
    {
        $user = $this->getUser();
        if ($user->phone == $this->phone) return;
        if (Customer::find()->where(['phone' => $this->phone])->count() > 0) {
            $this->addError($attribute, 'Số điện thoại bị trùng');
        }
    }

    public function validateManager($attribute, $params = [])
    {
        if (!$this->manager_id) return;
        $user = $this->getManager();
        if (!$user) {
            $this->addError($attribute, 'Nhân viên quản lý không tồn tại');
        }
    }

    public function getManager()
    {
        if (!$this->_manager) {
            $this->_manager = User::findOne($this->manager_id);
        }
        return $this->_manager;
    }

    public function fetchManager()
    {
        $users = User::find()->all();
        return ArrayHelper::map($users, 'id', 'name');
    }

	public function edit()
	{
		$connection = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
	        $user = $this->getUser();
            $user->name = $this->name;    
            $user->short_name = $this->short_name;    
            $user->phone = $this->phone;
            $user->address = $this->address;
            $user->email = $this->email;
            $user->manager_id = $this->manager_id;
	        $user->save();
			$transaction->commit();
			return $user;
		} catch (\Exception $e) {
			$transaction->rollBack();
			$this->addError('name', $e->getMessage());
			return false;
		}
	}

    public function getUser()
    {
        if (!$this->_customer) {
            $this->_customer = Customer::findOne($this->id);
        }
        return $this->_customer;
    }

    public function loadData($id)
    {
        $this->id = $id;
        $user = $this->getUser();
        $this->name = $user->name;
        $this->short_name = $user->short_name;
        $this->phone = $user->phone;
        $this->address = $user->address;
        $this->email = $user->email;
        $this->manager_id = $user->manager_id;
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Tên khách hàng',
            'short_name' => 'Tên thường gọi',
            'phone' => 'Số điện thoại',
            'address' => 'Địa chỉ',
            'email' => 'Thư điện tử',
            'manager_id' => 'Nhân viên quản lý',
        ];
    }
}
