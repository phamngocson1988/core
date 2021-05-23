<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\SupplierBank;
use supplier\models\Supplier;
use supplier\models\User;
use supplier\models\Bank;

class VerifySupplierBankForm extends \common\forms\ActionForm
{
    public $id;
    public $auth_key;
    public $supplier_id;

    protected $_supplierBank;

    public function rules()
    {
        return [
            [['id', 'auth_key'], 'trim'],
            [['id', 'auth_key', 'supplier_id'], 'required'],
            ['id', 'validateSupplierBank'],
            ['auth_key', 'validateAuthKey']
        ];
    }

    public function validateSupplierBank($attribute, $params = []) 
    {
        $supplierBank = $this->getSupplierBank();
        if (!$supplierBank) {
            return $this->addError($attribute, 'Tài khoản ngân hàng này không tồn tại');
        } elseif (!$supplierBank->isNotVerified()) {
            return $this->addError($attribute, 'Tài khoản ngân hàng này đã được xác nhận');
        }
    }

    public function validateAuthKey($attribute, $params = []) 
    {
        $supplierBank = $this->getSupplierBank();
        if ($supplierBank->auth_key !== $this->auth_key) {
            return $this->addError($attribute, 'Mã xác nhận không hợp lệ');
        }
    }

    public function getSupplierBank()
    {
        if (!$this->_supplierBank) {
            $this->_supplierBank = SupplierBank::find()->where([
                'id' => $this->id,
                'supplier_id' => $this->supplier_id
            ])->one();
        }
        return $this->_supplierBank;
    }

    public function verify()
    {
        if (!$this->validate()) return false;
        try {
            $model = $this->getSupplierBank();
            $model->verified = SupplierBank::VERIFIED_YES;
            return $model->save();
        } catch(Exception $e) {
            $this->addError('id', 'Có lỗi xảy ra' . $e->getMessage());
            return false;
        }
    }
}
