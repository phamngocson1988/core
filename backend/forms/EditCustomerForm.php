<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Customer;

class EditCustomerForm extends Model
{
    public $id;
	public $name;
    public $short_name;
    public $phone;

    protected $_customer;

	public function rules()
    {
        return [
            ['id', 'required'],
            
            ['name', 'trim'],
            ['name', 'required'],
            ['name', 'string', 'min' => 2, 'max' => 255],


            ['short_name', 'trim'],
            ['short_name', 'string', 'min' => 2, 'max' => 255],

            [['phone'], 'trim'],
        ];
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
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Tên khách hàng',
            'short_name' => 'Tên thường gọi',
            'phone' => 'Số điện thoại',
        ];
    }
}
