<?php
namespace backend\forms;

use Yii;
use common\models\User as Customer;
use common\models\TransactionHistory;
use yii\base\Model;

/**
 * TopupForm
 */
class TopupForm extends Model
{
    public $customer_id;
    public $amount;
    public $description;
    protected $_customer;
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['customer_id', 'amount'], 'required'],
            ['description', 'trim'],
            ['customer_id', 'validateCustomer']
        ];
    }

    public function validateCustomer($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $customer = $this->getCustomer();
            if (!$customer) {
                $this->addError($attribute, 'Khách hàng không hợp lệ');
            }
        }
    }

    public function getCustomer()
    {
        if (!$this->_customer) {
            $this->_customer = Customer::findOne($this->customer_id);
        }
        return $this->_customer;
    }

    public function topup()
    {
        if (!$this->validate()) return false;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            // Add a row to history table
            $history = new TransactionHistory();
            $history->user_id = $this->customer_id;
            $history->amount = $this->amount;
            $history->description = $this->description;
            $history->transaction_type = TransactionHistory::TYPE_INPUT;
            $history->created_by = Yii::$app->user->id;
            $history->save();
            // Re-count total of user amount          
            $customer = $this->getCustomer();  
            $customer->balance = $customer->reCountingBalance();
            $customer->save();
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
    }
}
