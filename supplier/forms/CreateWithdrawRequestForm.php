<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\behaviors\UserSupplierBehavior;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\Supplier;

class CreateWithdrawRequestForm extends Model
{
    public $supplier_id;
    public $bank_code;
    public $account_name;
    public $account_number;
    public $amount;

    protected $supplier;
    protected $_available;

    public function rules()
    {
        return [
            [['supplier_id', 'bank_code', 'account_name', 'account_number', 'amount'], 'required'],
            ['amount', 'filter', 'filter' => function ($value) {
                return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            }],
            ['amount', 'validateAmount']
        ];
    }

    public function validateAmount($attribute, $params = []) 
    {
        $available = $this->getAvailableAmount();
        if ($this->amount > $available) {
            $this->addError($attribute, sprintf('Bạn không thể yêu cầu rút số tiền nhiều hơn số dư khả dụng là %s', number_format($available)));
        }
    }

    public function getAvailableAmount()
    {
        if (!$this->_available) {
            $supplier = $this->getSupplier();
            $available = $supplier->walletTotal();
            $pending = SupplierWithdrawRequest::find()
            ->where(['supplier_id' => $this->supplier_id])
            ->andWhere(['IN', 'status', [SupplierWithdrawRequest::STATUS_REQUEST, SupplierWithdrawRequest::STATUS_APPROVE]])
            ->sum('amount');
            $this->_available = $available - $pending;
        }
        return $this->_available;
    }

    public function create()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getSupplier();
            $available = $this->getAvailableAmount();
            $model = new SupplierWithdrawRequest(['scenario' => SupplierWithdrawRequest::SCENARIO_CREATE]);
            $model->supplier_id = $this->supplier_id;
            $model->bank_code = $this->bank_code;
            $model->account_name = $this->account_name;
            $model->account_number = $this->account_number;
            $model->amount = $this->amount;
            $model->available_balance = $available - $this->amount;
            $result = $model->save(false);
            $transaction->commit();
            return $result;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

    public function fetchBanks()
    {
        $supplier = $this->getSupplier();
        return $supplier->banks;
    }

    public function getSupplier()
    {
        if (!$this->supplier) {
            $this->supplier =  Supplier::findOne($this->supplier_id);
        }
        return $this->supplier;
    }

}
