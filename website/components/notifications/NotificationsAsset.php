<?php

namespace website\components\notifications;

use yii\web\AssetBundle;

/**
 * Class NotificationsAsset
 *
 * @package webzop\notifications
 */
class NotificationsAsset extends \webzop\notifications\NotificationsAsset
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
        'js/notifications.js',
    ];

    /**
     * @inheritdoc
     */
    public $css = [
        'css/notifications.css',
    ];

    /**
     * @inheritdoc
     */
    public $depends = [
        'yii\web\JqueryAsset',
        'yii\web\YiiAsset',
    ];

}
