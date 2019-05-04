<?php
namespace backend\forms;

use Yii;
use common\models\User;

/**
 * EditCustomerForm
 */
class EditCustomerForm extends User
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
