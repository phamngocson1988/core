<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\Supplier;
use backend\models\OrderSupplier;
use backend\components\notifications\OrderNotification;

class AssignOrderSupplierForm extends Model
{
    public $order_id;
    public $supplier_id;
    public $requester;

    protected $_order;
    protected $_supplier;
    protected $_supplier_game;

    public function rules()
    {
        return [
            [['order_id', 'supplier_id', 'requester'], 'required'],
            ['order_id', 'validateOrder'],
            ['supplier_id', 'validateSupplier'],
        ];
    }

    public function getOrder()
    {
        if (!$this->_order) $this->_order = Order::findOne($this->order_id);
        return $this->_order;
    }

    public function getSupplier() 
    {
        if (!$this->_supplier) $this->_supplier = Supplier::findOne($this->supplier_id);
        return $this->_supplier;
    }

    public function getSupplierGame()
    {
        if (!$this->_supplier_game) {
            $supplier = $this->getSupplier();
            $order = $this->getOrder();
            $activeGames = $supplier->activeGames;
            $gameId = $order->game_id;
            $games = array_filter($activeGames, function ($game) use ($gameId) {
                return $game->game_id == $gameId;
            });
            $game = reset($games);
            $this->_supplier_game = $game;
        }
        return $this->_supplier_game;
    }

    public function validateOrder($attribute, $params = []) 
    {
        $order = $this->getOrder();
        // Check whether this order exist.
        if (!$order) {
            return $this->addError($attribute, 'Đơn hàng không tồn tại');
        }
        // Check order status
        $validStatus = in_array($order->status, [
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL,
        ]);
        if (!$validStatus) {
            return $this->addError($attribute, sprintf("Đơn hàng đang ở trạng thái %s nên không thể gửi NCC xử lý", $order->status));
        }
        // Check whether this order has supplier
        $processSupplier = OrderSupplier::find()->where([
            'order_id' => $order->id,
            'status' => [
                OrderSupplier::STATUS_REQUEST,
                OrderSupplier::STATUS_APPROVE,
                OrderSupplier::STATUS_PROCESSING,
            ]
        ])->one();
        if ($processSupplier) {
            return $this->addError($attribute, sprintf("Order đã có nhà cung cấp (%s) xử lý", $processSupplier->supplier_id));
        }
    }

    public function validateSupplier($attribute, $params = [])
    {
        $supplier = $this->getSupplier();
        if (!$supplier) $this->addError($attribute, 'Nhà cung cấp không tồn tại');
        if ($supplier->isDisabled()) $this->addError($attribute, 'Nhà cung cấp đang tạm ngưng');
        $game = $this->getSupplierGame();
        if (!$game) $this->addError($attribute, 'Nhà cung cấp không cung cấp game này');
    }

    public function assign()
    {
        if (!$this->validate()) return false;
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $settings = Yii::$app->settings;
            $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
            $game = $this->getSupplierGame();
            $order = $this->getOrder();
            $supplier = $this->getSupplier();

            // Final check to prevent duplicated assigning
            $countSupplier = OrderSupplier::find()->where([
                'order_id' => $order->id,
                'status' => [
                    OrderSupplier::STATUS_REQUEST,
                    OrderSupplier::STATUS_APPROVE,
                    OrderSupplier::STATUS_PROCESSING,
                ]
            ])->one();
            if ($countSupplier) {
                $this->addError('order_id', sprintf("Order đã có nhà cung cấp (%s) xử lý", $countSupplier->supplier_id));
                return false;
            }

            // distributed time
            $lastDistributedComplete = $order->completed_at ? $order->completed_at : $order->created_at;
            $date1 = strtotime($lastDistributedComplete);
            $date2 = strtotime('now');
            $mins = ($date2 - $date1) / 60;

            $orderSupplier = new OrderSupplier([
                'order_id' => $this->order_id,
                'supplier_id' => $this->supplier_id,
                'game_id' => $order->game_id,
                'price' => $game->price,
                'quantity' => $order->quantity - $order->doing_unit,
                'total_price' => 0,
                'rate_usd' => $rate,
                'status' => OrderSupplier::STATUS_REQUEST,
                'requested_by' => $this->requester,
                'requested_at' => date('Y-m-d H:i:s'),
                'distributed_time' => $mins,
            ]);
            $orderSupplier->save();

            $order->supplier_id = $this->supplier_id;
            $order->distributed_at = $order->distributed_at ? $order->distributed_at : date('Y-m-d H:i:s');
            $order->save();

            $order->log(sprintf("Chuyển đến nhà cung cấp %s (#%s)", $supplier->user->name, $this->supplier_id));
            $order->pushNotification(OrderNotification::NOTIFY_SUPPLIER_NEW_ORDER, $this->supplier_id);

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

}
