<?php

namespace supplier\components\notifications;

use yii\web\AssetBundle;

/**
 * Class PushNotificationsAsset
 *
 * @package webzop\notifications
 */
class PushNotificationsAsset extends \webzop\notifications\NotificationsAsset
{
    /**
     * @inheritdoc
     */
    public $basePath = '@webroot';
    public $baseUrl = '@web';

    /**
     * @inheritdoc
     */
    public $js = [
        'js/push_notifications.js',
    ];

    public $css = [
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

}
