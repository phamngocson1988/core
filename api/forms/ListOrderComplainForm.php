<?php
namespace api\forms;

use Yii;
use yii\base\Model;
use api\models\Order;
use api\models\OrderComplains;

class ListOrderComplainForm extends Model
{
    public $id;
    private $_order;

     /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
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

    public function fetch()
    {
        if (!$this->validate()) return false;
        $models = OrderComplains::find()->where(['order_id' => $this->id])->with('sender')->all();
        return array_map(function($model) {
            $sender = $model->sender;
            $senderName = $model->isCustomer() ? Yii::$app->user->identity->name : 'Admin';
            $object = [];
            $object['id'] = $model->id;
            $object['avatar'] = $sender->getAvatarUrl(null, null);
            $object['senderName'] = $senderName;
            $object['content'] = nl2br($model->content);
            $object['content_type'] = $model->content_type;
            // $object['created_at'] = \common\components\helpers\TimeElapsed::timeElapsed($model->created_at);
            $object['created_at'] = date('d/m/Y H:i', strtotime($model->created_at));
            $object['is_customer'] = $model->isCustomer();
            $object['ouath_sublink_client_id'] = $model->ouath_sublink_client_id;
            $object['user_sublink_id'] = $model->user_sublink_id;
            return $object;
        }, $models);
    }


    protected function getOrder()
    {
        if (!$this->_order) {
            $this->_order = Order::findOne($this->id);
        }
        return $this->_order;
    }
}

