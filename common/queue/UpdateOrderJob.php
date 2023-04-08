<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;
use yii\helpers\ArrayHelper;

class UpdateOrderJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var common\models\Order
     */
    public $order;

    /**
     * @var Array
     */
    public $changedAttributes;
    
    public function execute($queue)
    {
        // Temporary: process for change status to pending only
        // The first task is handle affiliate commission
        $oldOrder = $this->getOldOrder();
        $order = $this->order;
        if ($order->isPendingOrder() && !$oldOrder->isPendingOrder()) {
            // Run update affiliate
            $form = new \common\forms\CreateAffiliateCommissionForm(['order_id' => $order->id]);
            $form->setOrder($order);
            if (!$form->run()) {
                $errors = $form->getErrors();
                $this->handleQueueError($errors);
            }
        }
        
    }

    protected function getOldOrder()
    {
        $oldOrder = clone $this->order;
        foreach ($this->changedAttributes as $key => $value) {
            $oldOrder->$key = ArrayHelper::getValue($key, $value);
        }
        return $oldOrder;
    }

    protected function handleQueueError($errors)
    {
        $track = new Tracking();
        $description = sprintf("CreateAffiliateComission %s fail %s", $this->order->id, json_encode($errors));
        $track->description = $description;
        $track->save();
    }
}