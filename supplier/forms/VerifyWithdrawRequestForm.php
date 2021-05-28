<?php

namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\Supplier;
use supplier\models\User;
use supplier\models\SupplierBank;
use supplier\models\Bank;

class VerifyWithdrawRequestForm extends \common\forms\ActionForm
{
    public $id;
    public $auth_key;
    public $supplier_id;

    protected $_request;
    protected $_supplier;
    protected $_supplierBank;

    const SCENARIO_VERIFY = 'SCENARIO_VERIFY';
    const SCENARIO_SEND = 'SCENARIO_SEND';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_VERIFY] = ['id', 'supplier_id', 'auth_key'];
        $scenarios[self::SCENARIO_SEND] = ['id', 'supplier_id'];
        return $scenarios;
    }

    public function rules()
    {
        return [
            [['auth_key'], 'trim'],
            [['id', 'supplier_id'], 'required'],
            ['id', 'validateRequest'],
            ['auth_key', 'required', 'on' => self::SCENARIO_VERIFY],
            ['auth_key', 'validateAuthKey', 'on' => self::SCENARIO_VERIFY]
        ];
    }

    public function validateRequest($attribute, $params = []) 
    {
        $request = $this->getWithdrawRequest();
        if (!$request) {
            return $this->addError('auth_key', 'Yêu cầu rút tiền này không tồn tại');
        } elseif (!$request->isNotVerified()) {
            return $this->addError('auth_key', 'Yêu cầu rút tiền này đã được xác nhận');
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

    public function getSupplier()
    {
        if (!$this->_supplier) {
            $this->_supplier =  Supplier::findOne($this->supplier_id);
        }
        return $this->_supplier;
    }

    public function getSupplierBank()
    {
        if (!$this->_supplierBank) {
            $this->_supplierBank = SupplierBank::find()->where(['id' => $this->id])->one();
        }
        return $this->_supplierBank;
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

    public function send() 
    {
        if (!$this->validate()) return false;
        $model = $this->getWithdrawRequest();
        $model->auth_key = Yii::$app->security->generateRandomString(10);
        $model->save();
        $toEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        $siteName = Yii::$app->name;
        $supplier = $this->getSupplier();
        $bank = Bank::find()->where(['code' => $model->bank_code])->one();
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
        $banks = Bank::find()->all();
        $bankList = [];
        foreach ($banks as $bank) {
            $bankList[$bank->code] = sprintf("(%s) %s", $bank->code, $bank->short_name);
        }
        return $bankList;
    }
}
