<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\behaviors\UserSupplierBehavior;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\Supplier;
use supplier\models\SupplierBank;
use yii\helpers\ArrayHelper;

class CreateWithdrawRequestForm extends Model
{
    public $bank_id;
    public $supplier_id;
    public $bank_code;
    public $account_name;
    public $account_number;
    public $amount;

    protected $supplier;
    protected $_available;
    protected $_supplierBank;

    public function init() 
    {
        $supplierBank = $this->getSupplierBank();
        if ($supplierBank) {
            $this->bank_code = $supplierBank->bank_code;
            $this->account_name = $supplierBank->account_name;
            $this->account_number = $supplierBank->account_number;
        }
    }
    public function rules()
    {
        return [
            [['bank_id', 'supplier_id', 'bank_code', 'account_name', 'account_number', 'amount'], 'required'],
            ['amount', 'filter', 'filter' => function ($value) {
                return filter_var($value, FILTER_SANITIZE_NUMBER_INT);
            }],
            ['amount', 'validateAmount'],
            ['bank_id', 'validateBank']
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

    public function validateBank($attribute, $params = []) 
    {
        $bank = $this->getSupplierBank();
        if (!$bank) {
            return $this->addError($attribute, 'Ngân hàng không tồn tại');
        } elseif ($bank->isNotVerified()) {
            return $this->addError($attribute, 'Thông tin ngân hàng chưa được xác minh');
        }
    }

    public function getSupplierBank()
    {
        if (!$this->_supplierBank) {
            $this->_supplierBank = SupplierBank::find()->where(['id' => $this->bank_id])->one();
        }
        return $this->_supplierBank;
    }

    public function create()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getSupplier();
            $available = $this->getAvailableAmount();
            $supplierBank = $this->getSupplierBank();
            $model = new SupplierWithdrawRequest();
            $model->supplier_id = $this->supplier_id;
            $model->bank_code = $supplierBank->bank_code;
            $model->account_name = $supplierBank->account_name;
            $model->account_number = $supplierBank->account_number;
            $model->amount = $this->amount;
            $model->available_balance = $available - $this->amount;
            $model->auth_key = Yii::$app->security->generateRandomString(10);
            $result = $model->save();
            $transaction->commit();
            $this->sendVerifyEmail($model);
            return $result;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

    protected function sendVerifyEmail($model) 
    {
        $toEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        $siteName = Yii::$app->name;
        $supplier = $this->getSupplier();
        $supplierBank = $this->getSupplierBank();
        $bank = $supplierBank->bank;
        $user = $supplier->user;
        Yii::$app->supplier_mailer->compose('create_withdraw_request', [
            'model' => $model,
            'user' => $user,
            'bank' => $bank
        ])
        ->setTo($user->email)
        ->setFrom([$toEmail => $siteName])
        ->setSubject('[HoangGiaNapGame]- Xác nhận yêu cầu tạo mới tài khoản ngân hàng')
        ->setTextBody("[HoangGiaNapGame]- Xác nhận yêu cầu tạo mới tài khoản ngân hàng")
        ->send();
        return true;
    }


    public function fetchBanks()
    {
        return SupplierBank::find()->where(['supplier_id' => $this->supplier_id, 'verified' => SupplierBank::VERIFIED_YES])->all();
    }

    public function getSupplier()
    {
        if (!$this->supplier) {
            $this->supplier =  Supplier::findOne($this->supplier_id);
        }
        return $this->supplier;
    }

}
