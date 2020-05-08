<?php 
namespace frontend\components\notifications;

use Yii;
use yii\helpers\ArrayHelper;

class OrderNotification extends \common\components\notifications
{
    const KEY_NEW_ORDER = 'KEY_NEW_ORDER';

    /**
     * @var \frontend\models\Order the order object
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
        }
    }

    public function getDescription()
    {

        switch($this->key){
            case self::KEY_NEW_ORDER:
                return Yii::t('app', 'New account {user} created', ['user' => '#'.$this->userId]);
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
            ]
        ];
    }
}