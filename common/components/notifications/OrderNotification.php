<?php 
namespace common\components\notifications;

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
     * @var \common\models\Order the order object
     */
    public $order;
}