<?php
namespace common\components\wings;

use Yii;
use yii\base\BaseObject;

class RunOrderCommissionJob extends BaseObject implements \yii\queue\JobInterface
{
    public $id; // order id
    
    public function execute($queue)
    {
        $service = new Wings();
		$service->notifyStatus(['id' => $this->id, 'status' => $this->status]);
    }
}