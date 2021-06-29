<?php
namespace api\forms;

use Yii;
use yii\base\Model;
use api\models\Order;
use api\models\OrderComplains;

class CreateOrderComplainForm extends Model
{
    public $id;
    public $content;
    public $ouath_sublink_client_id;
    public $user_sublink_id;

    private $_order;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['id', 'content'], 'required'],
            [['ouath_sublink_client_id', 'user_sublink_id'], 'trim'],
            ['id', 'validateOrder'],
        ];
    }

    public function validateOrder($attribute, $params = [])
    {
        $order = $this->getOrder();
        if (!$order) {
            $this->addError($attribute, 'Order is not exist');
        } elseif ($order->customer_id != Yii::$app->user->id) {
            $this->addError($attribute, 'Order is not exist');
        }

    }

    public function create()
    {
        if (!$this->validate()) return false;
        $order = $this->getOrder();

        $model = new OrderComplains();
        $model->order_id = $order->id;
        $model->content = $this->content;
        $model->ouath_sublink_client_id = $this->ouath_sublink_client_id;
        $model->user_sublink_id = $this->user_sublink_id;
        $model->content_type = 'text';
        $model->object_name = OrderComplains::OBJECT_NAME_CUSTOMER;
        $model->save();

        $order->state = Order::STATE_PENDING_CONFIRMATION;
        $order->save();

        $supplier = $order->workingSupplier;
        if ($supplier) {
            $order->pushNotification(\api\components\notifications\OrderNotification::NOTIFY_SUPPLIER_NEW_ORDER_MESSAGE, $supplier->supplier_id);
        }
        return true;
    }


    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}

