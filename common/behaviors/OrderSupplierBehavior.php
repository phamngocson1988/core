<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use common\models\Supplier;
use common\models\OrderSupplier;

class OrderSupplierBehavior extends AttributeBehavior
{
    public function getSupplier()
    {
        $owner = $this->owner;
        $supplierTable = OrderSupplier::tableName();
        return $owner->hasOne(OrderSupplier::className(), ['order_id' => 'id'])
        ->andOnCondition(["IN", "{$supplierTable}.status", [
            OrderSupplier::STATUS_REQUEST,
            OrderSupplier::STATUS_APPROVE, 
            OrderSupplier::STATUS_PROCESSING,
        ]]);
    }

    public function getWorkingSupplier()
    {
        $owner = $this->owner;
        $supplierTable = OrderSupplier::tableName();
        return $owner->hasOne(OrderSupplier::className(), ['order_id' => 'id'])
        ->andOnCondition(["IN", "{$supplierTable}.status", [
            OrderSupplier::STATUS_APPROVE, 
            OrderSupplier::STATUS_PROCESSING,
        ]]);
    }

    public function getRequestingSupplier()
    {
        $owner = $this->owner;
        $supplierTable = OrderSupplier::tableName();
        return $owner->hasOne(OrderSupplier::className(), ['order_id' => 'id'])
        ->andOnCondition(["IN", "{$supplierTable}.status", [
            OrderSupplier::STATUS_REQUEST, 
        ]]);
    }

    public function getSuppliers()
    {
        $owner = $this->owner;
        $supplierTable = OrderSupplier::tableName();
        return $owner->hasMany(OrderSupplier::className(), ['order_id' => 'id'])
        ->andOnCondition(["IN", "{$supplierTable}.status", [
            OrderSupplier::STATUS_COMPLETED,
            OrderSupplier::STATUS_CONFIRMED, 
        ]]);
    }

    public function notifyNewOrderToSupplier($params = [])
    {
        $order = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'supplier_service_email', null);
        $fromName = sprintf("Hoàng gia nạp game");
        if (!$from) return;
        $orderSupplier = $order->getRequestingSupplier()->one();
        if (!$orderSupplier) return;
        $supplier = $orderSupplier->user;
        $to = $supplier->email;
        $title = sprintf("[HoangGiaNapGame] - ĐƠN HÀNG MỚI - Mã đơn hàng #%s", $order->id);
        try {
            return Yii::$app->supplier_mailer->compose('new_order', array_merge(['order' => $order], $params))
            ->setTo($to)
            ->setFrom([$from => $fromName])
            ->setSubject($title)
            ->setTextBody($title)
            ->send();
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function notifyConfirmOrderToSupplier($params = [])
    {
        $order = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'supplier_service_email', null);
        $fromName = sprintf("Hoàng gia nạp game");
        if (!$from) return;
        $orderSuppliers = $order->suppliers;
        if (!count($orderSuppliers)) return;
        
        foreach ($orderSuppliers as $key => $orderSupplier) {
            $supplier = $orderSupplier->user;
            $to = $supplier->email;
            $title = sprintf("[HoangGiaNapGame] - Xác nhận đơn hàng thành công- Mã đơn hàng #%s", $order->id);
            try {
                return Yii::$app->supplier_mailer->compose('confirm_order', array_merge(['order' => $order, 'orderSupplier' => $orderSupplier], $params))
                ->setTo($to)
                ->setFrom([$from => $fromName])
                ->setSubject($title)
                ->setTextBody($title)
                ->send();
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function notifyCancelOrderToSupplier($params = [])
    {
        $order = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'supplier_service_email', null);
        $fromName = sprintf("Hoàng gia nạp game");
        if (!$from) return;
        $orderSuppliers = $order->suppliers;
        if (!count($orderSuppliers)) return;
        
        foreach ($orderSuppliers as $key => $orderSupplier) {
            $supplier = $orderSupplier->user;
            $to = $supplier->email;
            $title = sprintf("[HoangGiaNapGame] - Thông báo huỷ đơn hàng- Mã đơn hàng #%s", $order->id);
            try {
                return Yii::$app->supplier_mailer->compose('cancel_order', array_merge(['order' => $order, 'orderSupplier' => $orderSupplier], $params))
                ->setTo($to)
                ->setFrom([$from => $fromName])
                ->setSubject($title)
                ->setTextBody($title)
                ->send();
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function notifyComplainOrderToSupplier($params = [])
    {
        $order = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'supplier_service_email', null);
        $fromName = sprintf("Hoàng gia nạp game");
        if (!$from) return;
        $orderSuppliers = $order->suppliers;
        if (!count($orderSuppliers)) return;
        
        foreach ($orderSuppliers as $key => $orderSupplier) {
            $supplier = $orderSupplier->user;
            $to = $supplier->email;
            $title = sprintf("[HoangGiaNapGame] - Thông báo khiếu nại đơn hàng- Mã đơn hàng #%s", $order->id);
            try {
                return Yii::$app->supplier_mailer->compose('complain_order', array_merge(['order' => $order, 'orderSupplier' => $orderSupplier], $params))
                ->setTo($to)
                ->setFrom([$from => $fromName])
                ->setSubject($title)
                ->setTextBody($title)
                ->send();
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }

    public function notifySupportOrderToSupplier($params = [])
    {
        $order = $this->owner; // the model which attached this behavior
        $settings = Yii::$app->settings;
        $from = $settings->get('ApplicationSettingForm', 'supplier_service_email', null);
        $fromName = sprintf("Hoàng gia nạp game");
        if (!$from) return;
        $orderSuppliers = $order->suppliers;
        if (!count($orderSuppliers)) return;
        
        foreach ($orderSuppliers as $key => $orderSupplier) {
            $supplier = $orderSupplier->user;
            $to = $supplier->email;
            $title = sprintf("[HoangGiaNapGame] - Phản hồi yêu cầu hỗ trợ đơn hàng- Mã đơn hàng #%s", $order->id);
            try {
                return Yii::$app->supplier_mailer->compose('support_order', array_merge(['order' => $order, 'orderSupplier' => $orderSupplier], $params))
                ->setTo($to)
                ->setFrom([$from => $fromName])
                ->setSubject($title)
                ->setTextBody($title)
                ->send();
            } catch (\Exception $e) {
                throw $e;
            }
        }
    }
}
