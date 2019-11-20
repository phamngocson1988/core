<?php

namespace reseller\components\toastr;

use yii\helpers\Html;
use yii\helpers\Json;
use lavrentiev\widgets\toastr\Notification;

class NotificationFlash extends \lavrentiev\widgets\toastr\NotificationFlash
{
    /** @var object $session */
    private $session;

    /**
     * @inheritdoc
     */
    public function run()
    {
        $this->session = \Yii::$app->session;

        $flashes = $this->session->getAllFlashes();
        foreach ($flashes as $type => $data) {
            $data = (array) $data;
            if (!in_array($type, $this->types)) continue;
            foreach ($data as $i => $message) {
                Notification::widget([
                    'type' => Html::encode($type),
                    'message' => Html::encode($message),
                    'options' => Json::decode((string) $this->options),
                ]);
            }

            $this->session->removeFlash($type);
        }
    }
}
