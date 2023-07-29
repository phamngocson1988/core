<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;
use common\models\Order;

class PurchaseOrderByWalletJob extends BaseObject implements \yii\queue\JobInterface
{
    public $user_id; // buyer
    public $order_id; // order
    
    public function execute($queue)
    {
        try {
            $track = new Tracking();
            $description = sprintf("PurchaseOrderByWalletJob %s-%s run", $this->user_id, $this->order_id);
            $track->description = $description;
            $track->save();
    
            $form = new \common\forms\PurchaseOrderByWalletForm(['order_id' => $this->order_id, 'user_id' => $this->user_id]);
            if (!$form->run()) {
                $errors = $form->getErrors();
                $track = new Tracking();
                $description = sprintf("PurchaseOrderByWalletJob %s-%s fail %s", $this->user_id, $this->order_id, json_encode($errors));
                $track->description = $description;
                $track->save();
            }
        } catch(\Exception $e) {
            sprintf('%s fail %s', __CLASS__, $this->id);
            return false;
        }
        
    }
}