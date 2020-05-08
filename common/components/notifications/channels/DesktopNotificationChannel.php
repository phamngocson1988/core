<?php

namespace common\components\notifications\channels;

use Yii;
use webzop\notifications\Channel;
use webzop\notifications\Notification;

class DesktopNotificationChannel extends Channel
{
    public function send(Notification $notification)
    {
        $db = Yii::$app->getDb();
        $className = $notification->className();
        $currTime = time();
        $db->createCommand()->insert('{{%desktop_notifications}}', [
            'class' => strtolower(substr($className, strrpos($className, '\\')+1, -12)),
            'key' => $notification->key,
            'title' => (string)$notification->getTitle(),
            'message' => (string)$notification->getDescription(),
            'route' => serialize($notification->getRoute()),
            'user_id' => $notification->userId,
            'created_at' => $currTime,
        ])->execute();
    }

}
