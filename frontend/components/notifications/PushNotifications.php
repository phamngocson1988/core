<?php

namespace frontend\components\notifications;

use Yii;
use webzop\notifications\widgets\Notifications as BaseNotifications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use frontend\components\notifications\PushNotificationsAsset;

class PushNotifications extends BaseNotifications
{
    public $pollInterval = 10000;
    public function registerAssets()
    {
        $this->clientOptions = array_merge([
            'url' => Url::to(['push-notification/list']),
            'deleteUrl' => Url::to(['push-notification/delete']),
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'pollInterval' => Html::encode($this->pollInterval),
            'icon' => 'https://file.kinggems.us/13/logo.png'
        ], $this->clientOptions);

        $js = 'PushNotifications(' . Json::encode($this->clientOptions) . ');';
        $view = $this->getView();
        PushNotificationsAsset::register($view);
        $view->registerJs($js);
    }
}
