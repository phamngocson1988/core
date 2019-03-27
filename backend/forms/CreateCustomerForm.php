<?php
namespace backend\forms;

use Yii;
use common\models\Customer;

/**
 * CreateCustomerForm
 */
class CreateCustomerForm extends Customer
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'company', 'tax_code'], 'required'],
            [['phone', 'address'], 'trim'],
        ];
    }

    public function init()
    {
        $hash = Yii::$app->security->generateRandomString(10);
        $this->username = $hash;
        $this->setPassword($hash);
        $this->generateAuthKey();
    }

    public function attributeLabels()
    {
        return [
            'name' => 'Người đại diện',
            'company' => 'Công ty',
            'phone' => 'Điện thoại',
            'address' => 'Địa chỉ',
            'tax_code' => 'Mã số thuế',
        ];
    }
}
