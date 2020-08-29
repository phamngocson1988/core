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
    public function getRoute()
    {
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
            'email' => [
                self::NOTIFY_NEW_PRICE,
                self::NOTIFY_IN_STOCK,
                self::NOTIFY_OUT_STOCK,
                self::NOTIFY_NEW_PROMOTION_FOR_GAME,
            ],
        ];
    }

    public function toEmail($channel)
    {
        $settings = Yii::$app->settings;
        $kinggemsMail = $settings->get('ApplicationSettingForm', 'customer_service_email');
        $kinggemsMailer = Yii::$app->mailer;
        $user = User::findOne($this->userId);
        $game = $this->game;
        Yii::$app->urlManagerFrontend->setHostInfo(Yii::$app->params['frontend_url']);

        $fromEmail = $kinggemsMail;
        $mailer = $kinggemsMailer;
        $toEmail = $user->email;
        $subject = '';
        $description = '';
        $template = '';
        $data = [];

        switch($this->key) {
            case self::NOTIFY_NEW_PRICE:
            case self::NOTIFY_IN_STOCK:
            case self::NOTIFY_OUT_STOCK:
            case self::NOTIFY_NEW_PROMOTION_FOR_GAME:
                $subject = sprintf('King Gems - #%s - Game Notification', $game->title);
                $description = $this->getDescription();
                $template = 'game_notification';
                $fromEmail = $kinggemsMail;
                $mailer = $kinggemsMailer;
                break;
        }
        
        $message = $mailer->compose($template, array_merge([
            'game' => $game,
            'notification' => $this,
            'user' => $user,
            'description' => $description,
            'game_url' => Yii::$app->urlManagerFrontend->createAbsoluteUrl(['game/view', 'id' => $game->id, 'slug' => $game->slug], true)
            ], $data));

        $message->setFrom($fromEmail);
        $message->setTo($toEmail);
        $message->setSubject($subject);
        $message->send($mailer);
    }
}