<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Order;
use backend\models\UserWallet;
use backend\models\UserCommission;
use yii\db\Exception;

class CancelOrderForm extends Model
{
    public $id;
    protected $_order;

    public function rules()
    {
        return [
            ['id', 'required'],
            ['id', 'validateOrder']
        ];
    }

    public function validateOrder($attribute, $params)
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Đơn hàng không tồn tại');
        }
        if (!$order->isPendingOrder() && !$order->isVerifyingOrder()) {
            $this->addError($attribute, 'Không thể hủy đơn hàng');
        }
    }

    public function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();
        $total_price = $order->total_price;
        $customer_id = $order->customer_id;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if (!$order->delete()) throw new Exception('Không thể xóa đơn hàng từ cơ sở dữ liệu.');
            if ($order->isPendingOrder()) {
                $wallet = new UserWallet();
                $wallet->type = UserWallet::TYPE_INPUT;
                $wallet->user_id = $customer_id;
                $wallet->coin = $total_price;
                $wallet->description = "Refund order #" . $order->id;
                $wallet->payment_at = date('Y-m-d H:i:s');
                $wallet->status = UserWallet::STATUS_COMPLETED;
                $wallet->save();
                if (!$wallet->save()) throw new Exception('Không thể hoàn tiền cho khách hàng từ cơ sở dữ liệu.');

            }
            $commission = UserCommission::findOne(['order_id' => $order->id]);
            if ($commission) $commission->delete();
            $transaction->commit();
            return true;
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }
        return false;
    }
}
