<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Cash;
use backend\models\CashAccount;
use yii\helpers\ArrayHelper;
use common\components\helpers\CommonHelper;

class CreateCashForm extends Model
{
    public $currency;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['currency', 'trim'],
            ['currency', 'required', 'message' => 'Bạn hãy chọn loại tiền tệ của ngân hàng'],
            ['currency', 'validateCash']
        ];
    }

    public function validateCash($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        if (Cash::find()->where(['currency' => $this->currency])->count() > 0) {
            $this->addError($attribute, 'Quỹ tiền mặt đã tồn tại');
        }
    }

    public function attributeLabels()
    {
        return [
            'currency' => 'Loại tiền tệ',
        ];
    }

    public function create()
    {
        $currencyList = $this->fetchCurrency();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            // create cash
            $bank = new Cash();
            $bank->name = sprintf("Quỹ tiền mặt %s", ArrayHelper::getValue($currencyList, $this->currency));        
            $bank->code = sprintf("CASH%s", $this->currency);
            $bank->country = $this->currency;
            $bank->currency = $this->currency;
            $bank->transfer_cost = 0;
            $bank->transfer_cost_type = 0;
            $bank->bank_type = Cash::BANK_TYPE_CASH;
            $bank->save();

            // create root account
            $account = new CashAccount();
            $account->account_name = $bank->name;        
            $account->account_number = $this->currency;
            $account->bank_id = $bank->id;
            $account->bank_type = CashAccount::BANK_TYPE_CASH;
            $account->currency = $this->currency;
            $account->root = CashAccount::ROOT_ACCOUNT;
            $account->save();
            
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            $this->addError('currency', $e->getMessage());
            return false;
        }
    }

    public function fetchCurrency()
    {
        return ArrayHelper::getValue(Yii::$app->params, 'currency', []);
    }

}
