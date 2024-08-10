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

                // Send notification mail to customer
                if ($newItem->isCompleted()) {
                    $user = $newItem->user;
                    $admin = Yii::$app->settings->get('ApplicationSettingForm', 'customer_service_email');
                    $siteName = 'Kinggems Us';
                    $after = $user->walletBalance();
                    $before = $after - $newItem->total_coin;
                    Yii::$app->mailer->compose('deposit_success', [
                        'id' => $newItem->id,
                        'name' => $user->getName(),
                        'amount' => $newItem->total_coin,
                        'before' => $before,
                        'after' => $after
                    ])
                    ->setTo($user->email)
                    ->setFrom([$admin => $siteName])
                    ->setSubject('KingGems – Deposit Successfully')
                    ->send();
                }
                
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
        $description = sprintf("%s: %s", __CLASS__, $data);
        $track->description = $description;
        $track->save();
    }
}