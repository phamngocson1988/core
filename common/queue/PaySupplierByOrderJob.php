<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;

class PaySupplierByOrderJob extends BaseObject implements \yii\queue\JobInterface
{
    public $id; // order id
    
    public function execute($queue)
    {
        try {
            $form = new \common\forms\PaySupplierByOrderForm([
                'order_id' => $this->id,
            ]);
            if (!$form->run()) {
                $errors = $form->getErrors();
                $track = new Tracking();
                $description = sprintf("PaySupplierByOrderJob %s fail %s", $this->id, json_encode($errors));
                $track->description = $description;
                $track->save();
            }
        } catch (\Exception $e) {
            sprintf('PaySupplierByOrderJob fail %s', $this->id);
            return false;
        }
    }
}