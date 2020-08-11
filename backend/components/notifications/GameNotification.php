<?php 
namespace backend\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use backend\models\User;
use backend\models\Game;

class GameNotification extends Notification
{
    const NOTIFY_NEW_PRICE = 'NOTIFY_NEW_PRICE';
    const NOTIFY_IN_STOCK = 'NOTIFY_IN_STOCK';
    const NOTIFY_OUT_STOCK = 'NOTIFY_OUT_STOCK';
    const NOTIFY_NEW_PROMOTION_FOR_GAME = 'NOTIFY_NEW_PROMOTION_FOR_GAME';

    /**
     * @var \backend\models\Game the order object
     */
    public $game;

    /**
     * @inheritdoc
     */
    public function getTitle()
    {
        switch($this->key) {
            case self::NOTIFY_NEW_PRICE:
                return "New price";
            case self::NOTIFY_IN_STOCK:
                return "In stock";
            case self::NOTIFY_OUT_STOCK:
                return "Out stock";
            case self::NOTIFY_NEW_PROMOTION_FOR_GAME:
                return "New promotion";
        }
    }

    public function getDescription()
    {
        $game = $this->game;
        switch($this->key) {
            case self::NOTIFY_NEW_PRICE:
                return sprintf("Game %s price has been updated", $game->title);
            case self::NOTIFY_IN_STOCK:
                return sprintf("Game %s is in-stock now", $game->title);
            case self::NOTIFY_OUT_STOCK:
                return sprintf("Game %s is out-stock now", $game->title);
            case self::NOTIFY_NEW_PROMOTION_FOR_GAME:
                return sprintf("Game %s has new promotion", $game->title);
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        $game = $this->game;
        return ['game/view', 'id' => $game->id, 'slug' => $game->slug];
    }

    public function shouldSend($channel)
    {
        $allows = $this->allow();
        $allowByChannel = ArrayHelper::getValue($allows, $channel->id, []);
        return in_array($this->key, $allowByChannel);
    }

    protected function allow()
    {
        return [
            'screen' => [
                self::NOTIFY_NEW_PRICE,
                self::NOTIFY_IN_STOCK,
                self::NOTIFY_OUT_STOCK,
                self::NOTIFY_NEW_PROMOTION_FOR_GAME,
            ],
        ];
    }
}