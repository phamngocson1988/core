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
        try {
            $settings = Yii::$app->settings;
            $am = $settings->get('ApplicationSettingForm', 'am_commission_rate');
            $ot = $settings->get('ApplicationSettingForm', 'ot_commission_rate');
            $form = new \common\forms\RunOrderCommissionForm([
                'order_id' => $this->id,
                'am_commission_rate' => round($am / 100, 2),
                'ot_commission_rate' => round($ot / 100, 2),
            ]);
            if (!$form->run()) {
                $errors = $form->getErrors();
                $track = new Tracking();
                $description = sprintf("RunOrderCommissionJob %s fail %s", $this->id, json_encode($errors));
                $track->description = $description;
                $track->save();
            }
        } catch(\Exception $e) {
            sprintf('%s fail %s', __CLASS__, $this->id);
            return false;
        }
        
    }
}