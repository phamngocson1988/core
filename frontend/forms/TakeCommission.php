<?php
namespace frontend\forms;

use Yii;
use frontend\models\UserCommission;
use frontend\models\UserWallet;

/**
 * Signup form
 */
class TakeCommission extends UserCommission
{
    public function rules()
    {
        return [
            ['id', 'validateCommission']
        ];
    }

    public function validateCommission($attribute, $params = null) 
    {
        if ($this->user_id != Yii::$app->user->id) $this->addError($attribute, "You don't have permission to take this commission"); 
        if ($this->isCompleted()) $this->addError($attribute, "This commission was completed");
        if ($this->isPending()) $this->addError($attribute, "This commission is pending now");
    }

    public function takeCommission()
    {
        if (!$this->validate()) return false;
        
        $transaction = Yii::$app->db->beginTransaction();
        try {
            $this->status = self::STATUS_COMPLETED;
            $this->save();
            $user = Yii::$app->user->identity;
            $wallet = new UserWallet();
            $wallet->coin = round($this->commission, 1);
            $wallet->balance = $user->getWalletAmount() + $wallet->coin;
            $wallet->type = UserWallet::TYPE_INPUT;
            $wallet->description = "Commission from order #" . $this->order_id;
            $wallet->created_by = $user->id;
            $wallet->user_id = $user->id;
            $wallet->status = UserWallet::STATUS_COMPLETED;
            $wallet->payment_at = date('Y-m-d H:i:s');
            $wallet->save();
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