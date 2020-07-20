<?php

namespace website\components\notifications;

use yii\web\AssetBundle;

/**
 * Class NotificationsAsset
 *
 * @package webzop\notifications
 */
class MessageNotificationsAsset extends \webzop\notifications\NotificationsAsset
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
        'js/message-notifications.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        // 'css/notifications.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

}
