<?php

namespace console\forms;

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
    public $max_reject = 3;
    public $delay_time = 30; //30 minutes

    protected $_order;
    protected $_supplier;
    protected $_supplier_game;

    public function rules()
    {
        return [
            [['order_id', 'supplier_id'], 'required'],
            ['order_id', 'validateOrder'],
            ['supplier_id', 'validateSupplier'],
            ['max_reject', 'validateMaxReject'],
            ['delay_time', 'validateDelayTime'],
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
        if (!$order) {
            return $this->addError($attribute, 'Đơn hàng không tồn tại');
        }

        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_PARTIAL])) {
            return $this->addError($attribute, sprintf("Không thể gửi qua nhà cung cấp vì đơn hàng %s đang ở trạng thái %s", $order->id, $order->status));
        }

        if ($order->supplier) {
            return $this->addError($attribute, 'Đơn hàng đã có nhà cung cấp');
        }        
    }

    public function validateDelayTime($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $delay_time = (int)$this->delay_time;
        if ($delay_time <= 0) return;
        $lastAssignment = OrderSupplier::find()
        ->where([
            'supplier_id' => $this->supplier_id,
            'order_id' => $this->order_id
        ])
        ->orderBy(['id' => SORT_DESC])
        ->limit(1)
        ->one();

        if (!$lastAssignment) return;
        $duration = strtotime('now') - strtotime($lastAssignment->retaken_at);
        if ($lastAssignment->status == OrderSupplier::STATUS_RETAKE
            && !$lastAssignment->retaken_by // be taken by system
            && $duration <= ($delay_time * 60)) {
            return $this->addError($attribute, sprintf('Đơn hàng này đã không nhận đơn chưa đầy % phút trước', $delay_time));
        }
    }

    public function validateMaxReject($attribute, $params = [])
    {
        if ($this->hasErrors()) return;
        $max_reject = (int)$this->max_reject;
        if ($max_reject <= 0) return;
        $lastThreeAssignments = OrderSupplier::find()
        ->where([
            'supplier_id' => $this->supplier_id,
            'order_id' => $this->order_id
        ])
        ->orderBy(['id' => SORT_DESC])
        ->limit($this->max_reject)
        ->all();
        $rejects = array_filter($lastThreeAssignments, function($obj) {
            return $obj->status == OrderSupplier::STATUS_RETAKE && !$obj->retaken_by;
        });
        if (count($rejects) == $max_reject) {
            return $this->add($attribute, sprintf("Nhà cung cấp này đã không nhận đơn %s lần", $max_reject));
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
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $settings = Yii::$app->settings;
            $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
            $game = $this->getSupplierGame();
            $order = $this->getOrder();
            $supplier = $this->getSupplier();
            $order->supplier_id = $this->supplier_id;
            $order->distributed_at = $order->distributed_at ? $order->distributed_at : date('Y-m-d H:i:s');
            $order->save();

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
                'requested_at' => date('Y-m-d H:i:s'),
                'distributed_time' => $mins,
            ]);
            $orderSupplier->save();
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
