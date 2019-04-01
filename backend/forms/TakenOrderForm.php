<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;
use common\models\Order;
use backend\forms\FetchOrderForm;

class TakenOrderForm extends Model
{
    public $user_id;
    public $order_id;

    protected $_user;
    protected $_order;


	public function rules()
    {
        return [
            [['user_id', 'order_id'], 'required'],
            ['user_id', 'validateUser'],
            ['user_id', 'validateCanTaken'],
            ['order_id', 'validateOrder'],
        ];
    }

    public function validateUser($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user) {
                $this->addError($attribute, Yii::t('app', 'user_not_exist', ['user' => '#' . $this->id]));
                return false;    
            }
        }
    }

    public function validateCanTaken($attribute, $params)
    {
        $checkTaken = new FetchOrderForm([
            'handler_id' => $this->user_id,
            'status' => Order::STATUS_PENDING
        ]);
        $checkTakenCommand = $checkTaken->getCommand();
        if ($checkTakenCommand->count()) {
            $this->addError($attribute, 'Bạn đang có đơn hàng chưa xử lý xong');
            return false;  
        }
    }

    public function validateOrder($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $order = $this->getOrder();
            if (!$order) {
                $this->addError($attribute, 'Đơn hàng không tồn tại');
                return false;    
            } elseif (!$order->isPendingOrder()) {
                $this->addError($attribute, 'Đơn hàng không cho phép nhận xử lý');
                return false;
            } elseif ($order->handler_id) {
                $this->addError($attribute, 'Đơn hàng đã có người xử lý');
                return false;
            }
        }
    }

    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findOne($this->user_id);
        }

        return $this->_user;
    }

    protected function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->order_id);
        }

        return $this->_order;
    }

    public function taken()
    {
        $order = $this->getOrder();
        $order->handler_id = $this->user_id;
        return $order->save();
    }
}
