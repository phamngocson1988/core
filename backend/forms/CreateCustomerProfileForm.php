<?php
namespace backend\forms;

use Yii;
use common\models\Profile;
use common\models\Customer;

/**
 * CreateProfileForm
 */
class CreateCustomerProfileForm extends Profile
{
    protected $_customer;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'prefix', 'port', 'action', 'price'], 'required'],
            ['action', 'default', 'value' => ['sms', 'call']],
            ['customer_id', 'validateCustomer']
        ];
    }

    public function validateCustomer($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getCustomer();
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'user_not_exist'));
            }
        }
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = Customer::findOne($this->customer_id);
        }
        return $this->_customer;
    }
}
