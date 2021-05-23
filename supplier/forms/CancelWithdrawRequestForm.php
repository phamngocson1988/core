<?php

namespace supplier\forms;

use Yii;
use supplier\models\SupplierWithdrawRequest;

class CancelWithdrawRequestForm extends \common\forms\ActionForm
{
    public $id;
    public $supplier_id;

    protected $_request;

    public function rules()
    {
        return [
            [['id'], 'trim'],
            [['id', 'supplier_id'], 'required'],
            ['id', 'validateRequest'],
        ];
    }

    public function validateRequest($attribute, $params = []) 
    {
        $request = $this->getWithdrawRequest();
        if (!$request) {
            return $this->addError($attribute, 'Yêu cầu rút tiền này không tồn tại');
        } elseif (!$request->isRequest()) {
            return $this->addError($attribute, 'Yêu cầu rút tiền này không thể huỷ');
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

    public function cancel()
    {
        if (!$this->validate()) return false;
        try {
            $model = $this->getWithdrawRequest();
            $model->cancelled_at = date('Y-m-d H:i:s');
            $model->cancelled_by = $this->supplier_id;
            $model->status = SupplierWithdrawRequest::STATUS_CANCEL;
            return $model->save();
        } catch(Exception $e) {
            $this->addError('id', 'Có lỗi xảy ra' . $e->getMessage());
            return false;
        }
    }
}
