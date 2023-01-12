<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;

class RunCustomerTrackerPerformanceJob extends BaseObject implements \yii\queue\JobInterface
{
    public $id; // lead tracker id
    
    public function execute($queue)
    {
        $form = new \common\forms\CalculateCustomerTrackerPerformanceForm(['id' => $this->id]);
        if (!$form->run()) {
            $errors = $form->getErrors();
            $track = new Tracking();
            $description = sprintf("RunCustomerTrackerPerformanceJob %s fail %s", $this->id, json_encode($errors));
            $track->description = $description;
            $track->save();
        }
    }
}