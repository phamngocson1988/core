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
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $settings = Yii::$app->settings;
            $rate = $settings->get('ApplicationSettingForm', 'exchange_rate_vnd', 23000);
            $game = $this->getSupplierGame();
            $order = $this->getOrder();
            $order->supplier_id = $this->supplier_id;
            $order->save();
            $orderSupplier = new OrderSupplier([
                'order_id' => $this->order_id,
                'supplier_id' => $this->supplier_id,
                'price' => $game->price,
                'quantity' => 0,
                'total_price' => 0,
                'rate_usd' => $rate,
                'status' => OrderSupplier::STATUS_REQUEST,
                'requested_by' => $this->requester,
                'requested_at' => date('Y-m-d H:i:s')
            ]);
            $orderSupplier->save();

            $from = $settings->get('ApplicationSettingForm', 'customer_service_email', null);
            $fromName = sprintf("%s Administrator", Yii::$app->name);
            $supplier = $orderSupplier->user;
            $to = $supplier->email;
            $title = sprintf("You have received new request for processing order #%s", $order->id);
            Yii::$app->mailer->compose('notify_supplier_new_request', [
                'order' => $order, 
                'title' => $title, 
                'supplier' => $supplier
            ])
            ->setTo($to)
            ->setFrom([$from => $fromName])
            ->setSubject($title)
            ->setTextBody($title)
            ->send();

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            return false;
        }
    }

}
