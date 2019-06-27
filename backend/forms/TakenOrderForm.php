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
    public $order_id;
    protected $_order;

	public function rules()
    {
        return [
            [['order_id'], 'required'],
            ['order_id', 'validateOrder'],
        ];
    }

    public function validateOrder($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $order = $this->getOrder();
            if (!Yii::$app->user->can('taken_order', ['order' => $order])) {
                $this->addError($attribute, 'Bạn không thể nhận xử lý đơn hàng này');
            }
        }
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
        $order->orderteam_id = Yii::$app->user->id;
        $order->process_start_time = date('Y-m-d H:i:s');
        return $order->save();
    }
}
