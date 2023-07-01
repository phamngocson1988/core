<?php
namespace common\queue;

use Yii;
use yii\base\BaseObject;
use common\models\Tracking;
use common\models\PaymentTransaction;
use yii\helpers\ArrayHelper;

class UpdatePaymentTransactionJob extends BaseObject implements \yii\queue\JobInterface
{
    /**
     * @var array - payment_transaction attributes
     */
    public $model;

    /**
     * @var Array
     */
    public $changedAttributes;
    
    public function execute($queue)
    {
        try {
            $oldItem = $this->getOldItem();
            $newItem = $this->getNewItem();
            if ($oldItem->status != $newItem->status) {
                // Send notification to wings
                $key = sprintf("payment:%s:status", $newItem->id);
                $value = [
                    'payment_transaction' => $newItem->id,
                    'status' => $newItem->status,
                ];
                Yii::$app->redis->set($key, json_encode($value));
            }
        } catch (\Exception $e) {
            $this->handleLog("fail process $e->getMessage()");
        }
    }

    protected function getOldItem()
    {
        return new PaymentTransaction(array_merge($this->model, $this->changedAttributes));
    }

    protected function getNewItem()
    {
        return new PaymentTransaction($this->model);
    }

    protected function handleLog($data)
    {
        $track = new Tracking();
        $description = sprintf("CreateAffiliateComission: %s", $data);
        $track->description = $description;
        $track->save();
    }
}