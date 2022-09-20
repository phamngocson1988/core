<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;

class RunOrderCommissionJob extends BaseObject implements \yii\queue\JobInterface
{
    public $id; // order id
    
    public function execute($queue)
    {
        $form = new \common\forms\RunOrderCommissionForm(['order_id' => $this->id]);
        if (!$form->run()) {
            $errors = $form->getErrors();
            $track = new Tracking();
            $description = sprintf("RunOrderCommissionJob %s fail %s", $this->id, json_encode($errors));
            $track->description = $description;
            $track->save();
        }
    }
}