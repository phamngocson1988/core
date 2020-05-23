<?php 
namespace frontend\components\notifications;

use Yii;
use yii\helpers\ArrayHelper;
use webzop\notifications\Notification;

class DepositNotification extends Notification
{
    const NOTIFY_SALER_NEW_ORDER = 'NOTIFY_SALER_NEW_ORDER';
    /**
     * @var \frontend\models\Order the order object
     */
    public $transaction;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
                return sprintf("[Giao dịch mới] - #%s", $this->transaction->id);

        }
    }

    public function getDescription()
    {

        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
                return sprintf("Chờ duyệt giao dịch");
        }
    }

    public function getIcon()
    {
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
                return 'https://kinggems.us/images/logo_icon.png';

        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
                return '';
        }
    }

    public function shouldSend($channel)
    {
        $allows = $this->allow();
        $allowByChannel = ArrayHelper::getValue($allows, $channel->id, []);
        $re = in_array($this->key, $allowByChannel);
        return $re;
    }

    protected function allow()
    {
        return [
            'desktop' => [
                self::NOTIFY_SALER_NEW_ORDER,
            ]
        ];
    }
}