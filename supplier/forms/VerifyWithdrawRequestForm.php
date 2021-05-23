<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\Supplier;
use supplier\models\User;
use supplier\models\Bank;

class VerifyWithdrawRequestForm extends \common\forms\ActionForm
{
    public $id;
    public $auth_key;
    public $supplier_id;

    protected $_request;

    public function rules()
    {
        return [
            [['id', 'auth_key'], 'trim'],
            [['id', 'auth_key', 'supplier_id'], 'required'],
            ['id', 'validateRequest'],
            ['auth_key', 'validateAuthKey']
        ];
    }

    public function validateRequest($attribute, $params = []) 
    {
        $request = $this->getWithdrawRequest();
        if (!$request) {
            return $this->addError($attribute, 'Yêu cầu rút tiền này không tồn tại');
        } elseif (!$request->isNotVerified()) {
            return $this->addError($attribute, 'Yêu cầu rút tiền này đã được xác nhận');
        }
    }

    public function validateAuthKey($attribute, $params = []) 
    {
        $request = $this->getWithdrawRequest();
        if ($request->auth_key !== $this->auth_key) {
            return $this->addError($attribute, 'Mã xác nhận không hợp lệ');
        }
    }

    public function getWithdrawRequest()
    {
        if (!$this->_request) {
            $this->_request = SupplierWithdrawRequest::find()->where([
                'id' => $this->id,
                'supplier_id' => $this->supplier_id
            ])->one();
        }
        return $this->_request;
    }

    public function verify()
    {
        if (!$this->validate()) return false;
        try {
            $model = $this->getWithdrawRequest();
            $model->verified = SupplierWithdrawRequest::VERIFIED_YES;
            return $model->save();
        } catch(Exception $e) {
            $this->addError('id', 'Có lỗi xảy ra' . $e->getMessage());
            return false;
        }
    }
}
