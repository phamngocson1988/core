<?php

namespace frontend\components\notifications;

use Yii;
use webzop\notifications\widgets\Notifications as BaseNotifications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use frontend\components\notifications\NotificationsAsset;

class Notifications extends BaseNotifications
{
    public $options = [
        'class' => 'header-icon header-bell header-dropdown',
        'id' => 'header-notification',
    ];

    public $countOptions = [
        'tag' => 'p',
        'class' => 'mega-title',
        'id' => 'header-notification-count'
    ];

    protected function renderNavbarItem()
    {
        // Start notifications
        $html  = Html::beginTag('div', $this->options);
        $bell = Html::tag('i', '', ['class' => 'fas fa-bell']);
        $html .= Html::a($bell, 'javascript:;', ['class' => 'trans']);
        $html .= Html::tag('span', '', ['class' => 'js-action']);



        $html .= Html::beginTag('div', ['class' => 'dropdown-mega']);
        $html .= Html::beginTag('div', ['class' => 'dropdown-mega-inner']);

        // Count
        $count = self::getCountUnseen();
        $countOptions = $this->countOptions;
        $countTag = ArrayHelper::remove($countOptions, 'tag', 'p');
        $html .= Html::tag($countTag, sprintf("Notifications (%s)", number_format($count)), $countOptions);

        // List
        $html .= Html::beginTag('div', ['class' => 'mega-content']);
        $html .= Html::beginTag('ul', ['class' => 'bell-list']);

        $html .= Html::endTag('ul');
        $html .= Html::endTag('div');

        // Actions
        $markRead = Html::tag('span', 'Mark as read', ['class' => 'read-all']);
        $viewAll = Html::a('View All', Url::to(['notification/index']), ['class' => 'trans mg-left']);
        $settings = Html::a('', Url::to(['profile/setting']), ['class' => 'fas fa-cog']);
        $html .= Html::tag('div', $markRead . $viewAll . $settings, ['class' => 'mega-btn']);
        
        $html .= Html::endTag('div');
        $html .= Html::endTag('div');
        $html .= Html::endTag('div');

        return $html;
    }

    public function registerAssets()
    {
        $this->clientOptions = array_merge([
            'id' => $this->options['id'],
            'url' => Url::to(['notification/list']),
            'countUrl' => Url::to(['notification/count']),
            'readUrl' => Url::to(['notification/read']),
            'readAllUrl' => Url::to(['notification/read-all']),
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'pollInterval' => Html::encode($this->pollInterval),
        ], $this->clientOptions);

        $js = 'Notifications(' . Json::encode($this->clientOptions) . ');';
        $view = $this->getView();

        NotificationsAsset::register($view);

        $view->registerJs($js);
    }
}
