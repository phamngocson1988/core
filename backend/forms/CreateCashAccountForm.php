<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\CashAccount;
use backend\models\Cash;
use backend\models\User;

class CreateCashAccountForm extends Model
{
    public $bank_id;
    public $user_id;

    protected $_user;
    protected $_bank;

    public function rules()
    {
        return [
            ['bank_id', 'trim'],
            ['bank_id', 'required', 'message' => 'Bạn phải chọn quỹ tiền mặt'],
            ['bank_id', 'validateBank'],

            ['user_id', 'trim'],
            ['user_id', 'required', 'message' => 'Bạn chọn nhân viên muốn tạo tài khoản'],
            ['user_id', 'validateUser']
        ];
    }

    public function validateBank($attribute, $params) 
    {
        $bank = $this->getBank();
        if (!$bank) {
            $this->addError($attribute, 'Quỹ tiền mặt này không tồn tại');
        }
    }

    public function validateUser($attribute, $params) 
    {
        if ($this->hasErrors()) return;
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, 'Nhân viên này không tồn tại');
            return;
        }
        $count = CashAccount::find()
        ->andWhere(['account_number' => $this->user_id])
        ->andWhere(['bank_id' => $this->bank_id])
        ->count();
        if ($count) {
            $this->addError($attribute, 'Nhân viên này đã có tài khoản tiền mặt');
        }
    }

    public function attributeLabels()
    {
        return [
            'user_id' => 'Nhân viên',
            'bank_id' => 'Quỹ tiền mặt',
        ];
    }

    public function create()
    {
        $bank = $this->getBank();
        $user = $this->getUser();
        $account = new CashAccount();
        $account->account_name = $user->name;        
        $account->account_number = $user->id;
        $account->bank_id = $this->bank_id;
        $account->bank_type = CashAccount::BANK_TYPE_CASH;
        $account->currency = $bank->currency;
        
        return $account->save() ? $account : null;
    }

    public function fetchBank()
    {
        $banks = Cash::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }

    public function fetchUser()
    {
        $banks = User::find()->all();
        return ArrayHelper::map($banks, 'id', 'name');
    }

    public function getBank()
    {
        if (!$this->_bank) {
            $this->_bank = Cash::findOne($this->bank_id);
        }
        return $this->_bank;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }
}
