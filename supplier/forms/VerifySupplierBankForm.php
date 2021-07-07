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

    protected $_supplier;

    const SCENARIO_VERIFY = 'SCENARIO_VERIFY';
    const SCENARIO_SEND = 'SCENARIO_SEND';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_VERIFY] = ['id', 'supplier_id', 'auth_key'];
        $scenarios[self::SCENARIO_SEND] = ['id', 'supplier_id'];
        return $scenarios;
    }

    protected $_supplierBank;

    public function rules()
    {
        return [
            [['auth_key'], 'trim'],
            [['id', 'supplier_id'], 'required'],
            ['id', 'validateSupplierBank'],
            ['auth_key', 'required', 'on' => self::SCENARIO_VERIFY],
            ['auth_key', 'validateAuthKey', 'on' => self::SCENARIO_VERIFY]
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

    public function getSupplier() 
    {
        if (!$this->_supplier) {
            $this->_supplier = Supplier::find()->where(['user_id' => $this->supplier_id])->one();
        }
        return $this->_supplier;
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

    public function send() 
    {
        if (!$this->validate()) return false;
        $model = $this->getSupplierBank();
        $model->auth_key = Yii::$app->security->generateRandomString(10);
        $model->save();
        $toEmail = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
        $siteName = Yii::$app->name;
        $supplier = $this->getSupplier();
        $bank = $model->bank;
        $user = $supplier->user;
        Yii::$app->supplier_mailer->compose('create_bank', [
            'model' => $model,
            'user' => $user,
            'bank' => $bank
        ])
        ->setTo($user->email)
        ->setFrom(['napgamehoanggia@gmail.com' => 'Hoàng Gia'])
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
