<?php
namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\User;
use supplier\behaviors\UserSupplierBehavior;

class UnclockAccountForm extends Model
{
    public $password;

    public function rules()
    {
        return [
            // username and password are both required
            ['password', 'required'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            $supplier = $this->getSupplier();
            if (!$user) {
                return $this->addError($attribute, 'Bạn cần phải login lại');
            }
            if (!$supplier) {
                return $this->addError($attribute, 'Bạn không phải là một nhà cung cấp hợp lệ');
            }
            if ($supplier->password && $supplier->password != $this->password) {
            	return $this->addError($attribute, 'Password không chính xác');
            }
            if (!$supplier->password && !$user->validatePassword($this->password)) {
            	return $this->addError($attribute, 'Bạn vẫn chưa đổi password dành riêng cho nhà cung cấp. Hãy nhập password tài khoản để mở khoá.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     *
     * @return bool whether the user is logged in successfully
     */
    public function verify()
    {
        if (!$this->validate()) return false;
        Yii::$app->session->set(Yii::$app->user->advanceModeKey, true);
        return true;
    }

    protected function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

    protected function getSupplier()
    {
    	$user = $this->getUser();
        $user->attachBehavior('supplier', new UserSupplierBehavior);
    	return $user->supplier;
    }
}
