<?php
namespace common\components\wings;

use Yii;
use yii\base\BaseObject;

class WingsNotifyStatusJob extends BaseObject implements \yii\queue\JobInterface
{
    public $id; // order id
    public $status;
    
    public function execute($queue)
    {
        $service = new Wings();
		$service->notifyStatus(['id' => $this->id, 'status' => $this->status]);
    }
}