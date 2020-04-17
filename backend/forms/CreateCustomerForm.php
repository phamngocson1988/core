<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Customer;
use backend\models\User;

class CreateCustomerForm extends Model
{
    public $name;
    public $short_name;
    public $phone;
    public $address;
    public $email;
    public $manager_id;

    protected $_manager;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],

            ['short_name', 'trim'],
            ['short_name', 'string', 'min' => 2, 'max' => 255],

            ['phone', 'trim'],
            ['phone', 'required'],
            ['phone', 'unique', 'targetClass' => Customer::className(), 'message' => 'Số điện thoại bị trùng với một tài khoản khác'],
            ['phone', 'string', 'min' => 2, 'max' => 16],

            ['email', 'trim'],
            ['email', 'email', 'message' => 'Không đúng định dạng email'],
            ['email', 'string', 'max' => 255, 'message' => 'Thông tin hộp thư không được quá 255 ký tự'],

            ['address', 'string', 'max' => 255, 'message' => 'Thông tin địa chỉ không được quá 255 ký tự'],
            ['manager_id', 'validateManager']
        ];
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
    public function create()
    {
        $user = new Customer();
        $user->name = $this->name;        
        $user->short_name = $this->short_name;
        $user->phone = $this->phone;
        $user->address = $this->address;
        $user->email = $this->email;
        $user->manager_id = $this->manager_id;
        
        return $user->save() ? $user : null;
    }
}
