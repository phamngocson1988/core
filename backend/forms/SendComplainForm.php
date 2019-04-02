<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\Order;
use common\models\OrderComplains;
use common\models\OrderComplainTemplate;

class SendComplainForm extends Model
{
    public $order_id;
    public $template_id;

    protected $_template;
    protected $_order;


	public function rules()
    {
        return [
            [['order_id', 'template_id'], 'required'],
            ['template_id', 'validateTemplate'],
            ['order_id', 'validateOrder'],
        ];
    }

    public function validateTemplate($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $template = $this->getTemplate();
            if (!$template) {
                $this->addError($attribute, 'Mẫu phản hồi không tồn tại');
                return false;    
            }
        }
    }

    public function validateOrder($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $order = $this->getOrder();
            if (!$order) {
                $this->addError($attribute, 'Đơn hàng không tồn tại');
                return false;    
            }
        }
    }

    protected function getTemplate()
    {
        if ($this->_template === null) {
            $this->_template = OrderComplainTemplate::findOne($this->template_id);
        }

        return $this->_template;
    }

    protected function getOrder()
    {
        if ($this->_order === null) {
            $this->_order = Order::findOne($this->order_id);
        }

        return $this->_order;
    }

    public function send()
    {
        if (!$this->validate()) return false;
        $template = $this->getTemplate();
        $complain = new OrderComplains();
        $complain->order_id = $this->order_id;
        $complain->content = $template->content;
        $complain->is_read = 0;
        $complain->created_by = Yii::$app->user->id;
        return $complain->save();
    }
}
