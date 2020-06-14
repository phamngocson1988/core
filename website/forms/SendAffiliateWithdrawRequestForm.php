<?php
namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\AffiliateCommissionWithdraw;
use website\models\AffiliateAccount;
use website\models\AffiliateCommission;
use yii\helpers\ArrayHelper;

class SendAffiliateWithdrawRequestForm extends Model
{
    public $user_id;
    public $account_id;
    public $amount;

    protected $_account;
    protected $_available;

    public function rules()
    {
        return [
            [['account_id', 'amount', 'user_id'], 'required'],
            ['account_id', 'validateAccount'],
            // ['amount', 'validateAmount'],
        ];
    }

    public function validateAmount($attribute, $params = []) 
    {
        $available = $this->getAvailableAmount();
        if (!$available) {
            $this->addError($attribute, 'Your wallet is empty.');
            return;
        }
        if ($this->amount > $available) {
            $this->addError($attribute, sprintf('You cannot withdraw more than %s', number_format($available, 1)));
            return;
        }
    }

    public function validateAccount($attribute, $params = []) 
    {
        $account = $this->getAccount();
        if (!$account) {
            $this->addError($attribute, sprintf('Your account is not exist. Please choose another one'));
            return;
        }
        if ($account->user_id != $this->user_id) {
            $this->addError($attribute, sprintf('Your account is not exist. Please choose another one'));
            return;
        }
    }

    public function getAvailableAmount()
    {
        if (!$this->_available) {
            $this->_available = AffiliateCommission::find()
            ->where([
                'user_id' => $this->user_id,
                'status' => AffiliateCommission::STATUS_VALID
            ])
            ->andWhere(['<=', 'valid_to_date', date('Y-m-d H:i:s')])
            ->sum('commission');
        }
        return $this->_available;
    }

    public function getAccount()
    {
        if (!$this->_account) {
            $this->_account = AffiliateAccount::findOne($this->account_id);
        }
        return $this->_account;
    }

    public function create()
    {
        $account = $this->getAccount();
        $request = new AffiliateCommissionWithdraw();
        $request->user_id = $this->user_id;
        $request->amount = $this->amount;
        $request->note = sprintf("Payment Method: %s\nAccount ID: %s\nAccount Name: %s\nRegion: %s", $account->payment_method, $account->account_number, $account->account_name, $account->region);
        $request->status = AffiliateCommissionWithdraw::STATUS_REQUEST;
        return $request->save();
    }

    public function fetchAccounts()
    {
        $accounts = AffiliateAccount::find()->where(['user_id' => $this->user_id])->limit(4)->all();
        return ArrayHelper::map($accounts, 'id', function($obj) {
            return sprintf("%s<br/>%s", $obj->account_name, $obj->payment_method);
        });
    }
}
