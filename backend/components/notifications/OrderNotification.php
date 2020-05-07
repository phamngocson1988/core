<?php 
namespace backend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;

class OrderNotification extends Notification
{
    const KEY_NEW_ORDER = 'KEY_NEW_ORDER';
    const KEY_PENDING = 'KEY_PENDING';
    const KEY_PENDING_INFORMATION = 'KEY_PENDING_INFORMATION';
    const KEY_REQUEST_CANCEL = 'KEY_REJECT_CANCEL_REQUEST';
    const KEY_REJECT_CANCEL_REQUEST = 'KEY_REJECT_CANCEL_REQUEST';
    const KEY_APPROVE_CANCEL_REQUEST = 'KEY_APPROVE_CANCEL_REQUEST';
    const KEY_COMPLETE = 'KEY_COMPLETE';

    /**
     * @var \backend\models\Order the order object
     */
    public $order;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::KEY_NEW_ORDER:
                return Yii::t('app', 'New account {user} created', ['user' => '#'.$this->userId]);
            case self::KEY_PENDING:
                return Yii::t('app', 'Instructions to reset the password');
        }
    }

    public function getDescription()
    {

        switch($this->key){
            case self::KEY_NEW_ORDER:
                return Yii::t('app', 'New account {user} created', ['user' => '#'.$this->userId]);
            case self::KEY_PENDING:
                return Yii::t('app', 'Instructions to reset the password');
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        return ['/users/edit', 'id' => $this->user->id];
    }

    public function shouldSend($channel)
    {
        $allows = $this->allow();
        $allowByChannel = ArrayHelper::getValue($allow, $channel->id, []);
        return in_array($this->key, $allowByChannel);
    }

    protected function allow()
    {
        return [
            'desktop' => [
                self::KEY_NEW_ORDER,
                self::KEY_PENDING,
                self::KEY_PENDING_INFORMATION,
                self::KEY_REQUEST_CANCEL,
                self::KEY_REJECT_CANCEL_REQUEST,
                self::KEY_APPROVE_CANCEL_REQUEST,
                self::KEY_COMPLETE,
            ]
        ];
    }
}