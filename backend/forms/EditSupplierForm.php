<?php

namespace backend\forms;

use Yii;
use common\forms\SignupForm as BaseSignupForm;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;
use common\models\User;
use backend\models\Supplier;
use backend\behaviors\UserSupplierBehavior;

class EditSupplierForm extends BaseSignupForm
{
    public $id;
	public $name;
    public $username;
    public $email;
    public $advance_password;
    public $phone;
    public $address;
    public $birthday;
    public $status;

    protected $_user;
    protected $_supplier;

	public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateUser'],

            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['username', 'trim'],

            ['email', 'trim'],

            ['password', 'trim'],
            ['password', 'string', 'min' => 6],

            ['advance_password', 'trim'],

            [['username', 'password', 'advance_password'], function ($attribute, $params) {
                if (preg_match('/\s+/',$this->$attribute)) {
                     $this->addError($attribute, Yii::t('app', 'no_white_space_allowed')); //No white spaces allowed!
                }
            }],
            ['status', 'in', 'range' => array_keys(User::getUserStatus())],
            [['phone', 'address', 'birthday'], 'trim'],
            ['phone', 'match', 'pattern' => '/^[0-9]+((\.|\s)?[0-9]+)*$/i'],
        ];
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->addError($attribute, 'User không tồn tại');
        }
        $supplier = $this->getSupplier();
        if (!$supplier) {
            return $this->addError($attribute, 'User không phải là nhà cung cấp');
        }
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Nhà cung cấp',
            'username' => 'Tên đăng nhập',
            'email' => 'Hộp thư',
            'password' => 'Mật khẩu',
            'advance_password' => 'Mật khẩu nâng cao',
            'phone' => 'Điện thoại',
            'address' => 'Địa chỉ',
            'birthday' => 'Ngày sinh',
            'status' => 'Trạng thái',
        ];
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
            // $user->setScenario(User::SCENARIO_EDIT);
	        $user->name = $this->name;        
            $user->phone = $this->phone;
            $user->address = $this->address;
            $user->birthday = $this->birthday;
            $user->status = $this->status;
            if ($this->password) {
                $user->setPassword($this->password);
                $user->generateAuthKey();
            }
	        $user->save();
	        
            $supplier = $this->getSupplier();
            // $supplier->setScenario(Supplier::SCENARIO_EDIT);
            $supplier->password = $this->advance_password;
            $supplier->save();

   			$transaction->commit();
			// $this->sendEmail();
            Yii::$app->syslog->log('edit_supplier', 'edit new supplier', $user);
			return $user;
		} catch (\Exception $e) {
			$transaction->rollBack();
			$this->addError('username', $e->getMessage());
			return false;
		}
	}

    public function loadData()
    {
        $user = $this->getUser();
        $supplier = $this->getSupplier();
        $this->name = $user->name;
        $this->username = $user->username;
        $this->email = $user->email;
        $this->advance_password = $supplier->password;
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

    public function getSupplier()
    {
        if (!$this->_supplier) {
            $user = $this->getUser();
            if ($user) {
                $user->attachBehavior('supplier', new UserSupplierBehavior);
                $this->_supplier = $user->supplier;
            }
        }
        return $this->_supplier;
    }

    public function getUserStatus()
    {
        return User::getUserStatus();
    }
}
