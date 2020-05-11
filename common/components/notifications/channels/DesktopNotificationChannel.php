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
        $data = [
            'class' => strtolower(substr($className, strrpos($className, '\\')+1, -12)),
            'key' => $notification->key,
            'title' => (string)$notification->getTitle(),
            'icon' => method_exists($notification, 'getIcon') ? (string)$notification->getIcon() : '',
            'message' => (string)$notification->getDescription(),
            'device' => 'desktop',
            'route' => serialize($notification->getRoute()),
            'user_id' => $notification->userId,
            'created_at' => $currTime,
        ];
        $db->createCommand()->insert('{{%device_notifications}}', $data)->execute();
    }

}
