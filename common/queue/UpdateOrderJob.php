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
            $oldOrder = $this->getOldOrder();
            $order = $this->getOrder();
            // handling affiliate commission when the order completed
            if ($order->isCompletedOrder() && !$oldOrder->isCompletedOrder()) {
                // Run update affiliate
                $form = new \common\forms\CreateAffiliateCommissionForm(['order_id' => $order->id]);
                $form->setOrder($order);
                if (!$form->run()) {
                    $errors = $form->getErrors();
                    $this->handleQueueError($errors);
                }
            }
            // Notify to wings when order status changed
            if ($order->reseller_id) {
                // Send notification to wings
                $key = sprintf("order_data:%s:status", $order->id);
                $value = [
                    'server_order_id' => $order->id,
                    'quantity' => $order->quantity,
                    'processed_quantity' => $order->doing_unit,
                    'note' => $order->note,
                    'status' => $order->status,
                    'updated_by' => 1,
                    'completed_at' => $order->completed_at
                ];
                Yii::$app->redis->set($key, json_encode($value));
            }

            // Send mail to customer when order status changed to processing
            if ($order->isProcessingOrder() && $oldOrder->isPendingOrder()) {
                $settings = Yii::$app->settings;
                $kinggemsMail = $settings->get('ApplicationSettingForm', 'customer_service_email');
                $kinggemsMailer = Yii::$app->mailer;
                $subject = sprintf('KingGems - #%s - Processing', $order->id);
                $template = 'order_processing';
                $fromEmail = $kinggemsMail;
                $toEmail = $order->customer->email;
                $mailer = $kinggemsMailer;
                $message = $mailer->compose($template, [
                    'order' => $order,
                ]);
        
                $message->setFrom($fromEmail);
                $message->setTo($toEmail);
                $message->setSubject($subject);
                $message->send($mailer);
                $order->log(sprintf("admin notification mail processing order to %s", $toEmail));
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
        $order = $this->getOrder();
        $description = sprintf("%s %s fail %s", __CLASS__, $order->id, json_encode($errors));
        print_r($description);

        $track->description = $description;
        $track->save();
    }

    protected function handleLog($data)
    {
        $track = new Tracking();
        $description = sprintf("%s: %s", __CLASS__, $data);
        $track->description = $description;
        $track->save();
    }
}