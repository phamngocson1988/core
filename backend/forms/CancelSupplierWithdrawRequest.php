<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\SupplierWithdrawRequest;
use yii\db\Exception;

class CancelSupplierWithdrawRequest extends Model
{
    public $id;
    public $note;
    protected $_request;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['note', 'trim'],
            ['id', 'validateRequest']
        ];
    }

    public function validateRequest($attribute, $params)
    {
        $model = $this->getRequest();
        if (!$model) {
            $this->addError($attribute, 'Yêu cầu rút tiền này không tồn tại');
        }
        if ($model->isDone() || $model->isCancel()) {
            $this->addError($attribute, 'Không thể hủy đơn hàng');
        }
    }

    public function getRequest()
    {
        if (!$this->_request) {
            $this->_request = SupplierWithdrawRequest::findOne($this->id);
        }
        return $this->_request;
    }

    public function cancel()
    {
        if (!$this->validate()) return false;
        $request = $this->getRequest();
        $request->setScenario(SupplierWithdrawRequest::SCENARIO_CANCEL);
        $transaction = Yii::$app->db->beginTransaction();
        try {
        	$request->note = $this->note;
        	$request->cancelled_at = date('Y-m-d H:i:s');
            $request->cancelled_by = Yii::$app->user->id;
            $request->status = SupplierWithdrawRequest::STATUS_CANCEL;
            if (!$request->save()) throw new Exception('Quá trình diễn ra bị lỗi.');
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return false;
    }
}
