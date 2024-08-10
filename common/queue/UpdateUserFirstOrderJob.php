<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Order;
use common\models\User;

class UpdateUserFirstOrderJob extends BaseObject implements \yii\queue\JobInterface
{
    public $order_id; // order id
    public $user_id; // order id
    
    public function execute($queue)
    {
        try {
            $user = User::find()->select(['id', 'first_order_at'])->where(['id' => $this->user_id])->one();
            if ($user && !$user->first_order_at) {
              $order = Order::find()->select(['created_at'])->where(['id' => $this->order_id])->one();
              $user->first_order_at = $order->created_at;
              $user->save();
            }
            return true;
        } catch(\Exception $e) {
            sprintf('%s fail %s', __CLASS__, $this->id);
            return false;
        }
    }
}