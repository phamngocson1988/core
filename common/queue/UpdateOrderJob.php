<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;
use common\models\Order;
use yii\helpers\ArrayHelper;

class UpdateOrderJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var array - order attributes
     */
    public $order;

    /**
     * @var Array
     */
    public $changedAttributes;
    
    public function execute($queue)
    {
        try {
            // Temporary: process for change status to pending only
            // The first task is handle affiliate commission
            $oldOrder = $this->getOldOrder();
            $order = $this->getOrder();
            if ($order->isPendingOrder() && $oldOrder->isVerifyingOrder()) {
                // Run update affiliate
                $form = new \common\forms\CreateAffiliateCommissionForm(['order_id' => $order->id]);
                $form->setOrder($order);
                if (!$form->run()) {
                    $errors = $form->getErrors();
                    $this->handleQueueError($errors);
                }
            }
        } catch (\Exception $e) {
            $this->handleLog("fail process $e->getMessage()");
        }
    }

    protected function getOldOrder()
    {
        return new Order(array_merge($this->order, $this->changedAttributes));
    }

    protected function getOrder()
    {
        return new Order($this->order);
    }

    protected function handleQueueError($errors)
    {
        $track = new Tracking();
        $description = sprintf("CreateAffiliateComission %s fail %s", $this->order->id, json_encode($errors));
        print_r($description);

        $track->description = $description;
        $track->save();
    }

    protected function handleLog($data)
    {
        $track = new Tracking();
        $description = sprintf("CreateAffiliateComission: %s", $data);
        $track->description = $description;
        $track->save();
    }
}