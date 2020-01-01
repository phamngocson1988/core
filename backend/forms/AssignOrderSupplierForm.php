<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Order;
use backend\models\Supplier;
use backend\models\OrderSupplier;
use backend\behaviors\OrderSupplierBehavior;

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
        if (!$order) $this->addError($attribute, 'Đơn hàng không tồn tại');
        if (!in_array($order->status, [Order::STATUS_PENDING, Order::STATUS_PROCESSING])) $this->addError($attribute, 'Tác vụ không hợp lệ');

        $order->attachBehavior('supplier', OrderSupplierBehavior::className());
        if ($order->supplier) $this->addError($attribute, 'Đơn hàng đã có nhà cung cấp');
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
        $game = $this->getSupplierGame();
        $orderSupplier = new OrderSupplier([
            'order_id' => $this->order_id,
            'supplier_id' => $this->supplier_id,
            'price' => $game->price,
            'quantity' => 0,
            'total_price' => 0,
            'status' => OrderSupplier::STATUS_REQUEST,
            'requested_by' => $this->requester,
            'requested_at' => date('Y-m-d H:i:s')
        ]);
        return $orderSupplier->save();
    }

}
