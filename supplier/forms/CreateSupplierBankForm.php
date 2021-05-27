<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\SupplierBank;
use supplier\models\Supplier;
use supplier\models\User;
use supplier\models\Bank;

class CreateSupplierBankForm extends Model
{
    public $supplier_id;
    public $bank_code;
    public $account_name;
    public $account_number;
    public $province;
    public $city;
    public $branch;

    protected $_supplier;
    protected $_bank;

    public function rules()
    {
        return [
            [['supplier_id', 'bank_code', 'account_number', 'account_name'], 'required',],
            ['supplier_id', 'validateSupplier'],
            ['bank_code', 'validateBank'],
            [['province', 'city', 'branch'], 'safe'],
        ];
    }

    public function validateSupplier($attribute, $params = []) 
    {
        $supplier = $this->getSupplier();
        if (!$supplier) {
            return $this->addError($attribute, 'Nhà cung cấp không tồn tại');
        }
    }

    public function getSupplier()
    {
        if (!$this->_supplier) {
            $this->_supplier = Supplier::find()->where(['user_id' => $this->supplier_id, 'status' => Supplier::STATUS_ENABLED])->one();
        }
        return $this->_supplier;
    }

    public function validateBank($attribute, $params = []) 
    {
        $bank = $this->getBank();
        if (!$bank) {
            return $this->addError($attribute, 'Ngân hàng này không tồn tại');
        }
    }

    public function getBank()
    {
        if (!$this->_bank) {
            $this->_bank = Bank::find()->where(['code' => $this->bank_code, 'status' => Bank::STATUS_ACTIVE])->one();
        }
        return $this->_bank;
    }

    public function create()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $supplier = $this->getSupplier();
            $model = new SupplierBank();
            $model->supplier_id = $this->supplier_id;
            $model->bank_code = $this->bank_code;
            $model->account_name = $this->account_name;
            $model->account_number = $this->account_number;
            $model->province = $this->province;
            $model->city = $this->city;
            $model->branch = $this->branch;
            $result = $model->save();
            // $this->sendVerifyEmail($model);
            $transaction->commit();
            return $model;
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
        $bank = $this->getBank();
        $user = $supplier->user;
        Yii::$app->supplier_mailer->compose('create_bank', [
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
        $banks = Bank::find()->all();
        $bankList = [];
        foreach ($banks as $bank) {
            $bankList[$bank->code] = sprintf("(%s) %s", $bank->code, $bank->short_name);
        }
        return $bankList;
    }
}
