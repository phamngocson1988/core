<?php 
namespace website\components\notifications;

use Yii;
use yii\helpers\ArrayHelper;
use webzop\notifications\Notification;

class OrderNotification extends Notification
{
    const NOTIFY_SALER_NEW_ORDER = 'NOTIFY_SALER_NEW_ORDER';
    const NOTIFY_ORDERTEAM_NEW_ORDER = 'NOTIFY_ORDERTEAM_NEW_ORDER';
    const NOTIFY_SALER_CANCEL_ORDER = 'NOTIFY_SALER_CANCEL_ORDER';
    const NOTIFY_ORDERTEAM_CANCEL_ORDER = 'NOTIFY_ORDERTEAM_CANCEL_ORDER';
    const NOTIFY_SUPPLIER_CANCEL_ORDER = 'NOTIFY_SUPPLIER_CANCEL_ORDER';
    /**
     * @var \website\models\Order the order object
     */
    public $order;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
                return sprintf("[Đơn hàng mới] - #%s", $this->order->id);
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                return sprintf("[Yêu cầu hủy] - #%s", $this->order->id);

        }
    }

    public function getIcon()
    {
        $setting = Yii::$app->settings;
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                return $setting->get('ApplicationSettingForm', 'logo', '');

        }
    }

    public function getDescription()
    {

        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
                return sprintf("Chờ duyệt giao dịch");
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
                return sprintf("Chờ phân phối");
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                return sprintf("Yêu cầu hủy đơn hàng");
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_SALER_NEW_ORDER:
            case self::NOTIFY_ORDERTEAM_NEW_ORDER:
            case self::NOTIFY_SALER_CANCEL_ORDER:
            case self::NOTIFY_ORDERTEAM_CANCEL_ORDER:
                return '';
            case self::NOTIFY_SUPPLIER_CANCEL_ORDER:
                return ['order/edit', 'id' => $this->order->id];
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
                self::NOTIFY_ORDERTEAM_NEW_ORDER,
                self::NOTIFY_SALER_CANCEL_ORDER,
                self::NOTIFY_ORDERTEAM_CANCEL_ORDER,
                self::NOTIFY_SUPPLIER_CANCEL_ORDER,
            ]
        ];
    }
}