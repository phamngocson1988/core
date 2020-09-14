<?php

namespace backend\forms;

use Yii;
use common\forms\SignupForm as BaseSignupForm;
use yii\helpers\ArrayHelper;
use backend\forms\AssignRoleForm;
use common\models\User;
use backend\models\Supplier;

class CreateSupplierForm extends BaseSignupForm
{
	public $name;
    public $username;
    public $email;
    public $password;
    public $advance_password;
    public $phone;
    public $address;
    public $birthday;
    public $status;

	public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['username', 'trim'],
            ['username', 'required'],
            ['username', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This username has already been taken.'],
            ['username', 'string', 'min' => 2, 'max' => 255],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'This email address has already been taken.'],

            ['password', 'required'],
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

	public function signup()
	{
		$connection = Yii::$app->db;
		$transaction = $connection->beginTransaction();
		try {
			if (!$this->validate()) {
	            return false;
	        }
	        
	        $user = new User();
	        $user->name = $this->name;        
	        $user->username = $this->username;
            $user->email = $this->email;
            $user->phone = $this->phone;
            $user->address = $this->address;
            $user->birthday = $this->birthday;
            $user->status = $this->status;
	        $user->setPassword($this->password);
	        $user->generateAuthKey();
            $user->is_supplier = User::IS_SUPPLIER;
	        $user->save();
	        
            $supplier = new Supplier();
            $supplier->setScenario(Supplier::SCENARIO_CREATE);
            $supplier->user_id = $user->id;
            $supplier->password = $this->advance_password;
            $supplier->save();

   			$transaction->commit();
			// $this->sendEmail();
            Yii::$app->syslog->log('create_supplier', 'create new supplier', $user);
			return $user;
		} catch (\Exception $e) {
			$transaction->rollBack();
			$this->addError('username', $e->getMessage());
			return false;
		}
	}

	public function sendEmail()
    {
        $settings = Yii::$app->settings;
        $adminEmail = $settings->get('ApplicationSettingForm', 'admin_email', null);
        $email = $this->email;
        return Yii::$app->mailer->compose('create_user', ['mail' => $this])
            ->setTo($email)
            ->setFrom([$adminEmail => Yii::$app->name])
            ->setSubject("[Kinggems][Invitation email] Bạn nhận được lời mời từ " . Yii::$app->name)
            ->setTextBody('Bạn nhận được lời mời làm thành viên quản trị từ kinggems.us')
            ->send();
    }

    public function getUserStatus()
    {
        return User::getUserStatus();
    }
}
