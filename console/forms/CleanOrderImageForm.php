<?php

namespace console\forms;

use Yii;
use common\models\Order;
use common\models\OrderFile;
use common\models\Tracking;

class CleanOrderImageForm extends ActionForm
{
    public $durationMonth;

    public function rules()
    {
        return [
            ['durationMonth', 'integer', 'min' => 1]
        ];
    }
    /**
     * TODO: 
     * - Validate
     * - Fetch all order created before $durationMonth
     * - Clean their order images in order_file table and physical files
     */
    public function run() 
    {
        if (!$this->validate()) return false;
        try {
            $durationMonth = $this->durationMonth;
            $endTime = date('Y-m-d 00:00:00', strtotime("-$durationMonth month"));
            $startTime = date('Y-m-d 00:00:00', strtotime("$endTime -1 month"));
            $orders = Order:: find()
            ->where(['between', 'created_at', $startTime, $endTime])
            ->all();

            foreach ($orders as $order) {
                $orderFiles = OrderFile::find()->where(['order_id' => $order->id])->all();
                foreach ($orderFiles as $orderFile) {
                    $order->log(sprintf("Delete file %s of order %s", $orderFile->file_id, $orderFile->order_id));
                    $orderFile->delete();
                }
            }
        } catch (\Exception $e) {
            $track = new Tracking();
            $track->description = sprintf("Exception CleanOrderImageForm: %s", $e->getMessage());
            $track->save();
        }
    }    
}
