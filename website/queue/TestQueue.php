<?php
namespace website\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;

class TestQueue extends BaseObject implements \yii\queue\JobInterface
{
    public $user_id;
    
    public function execute($queue)
    {
        $track = new Tracking();
        $description = $this->user_id ? sprintf("Running queue be %s", $this->user_id) : 'Anonyous user';
        $track->description = $description;
        $track->save();
    }
}