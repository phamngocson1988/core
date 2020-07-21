<?php

namespace website\components\notifications;

use Yii;
use webzop\notifications\widgets\Notifications as BaseNotifications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use website\components\notifications\NotificationsAsset;

class Notifications extends BaseNotifications
{
    public $options = [
        'class' => 'nav-item dropdown d-inline-block p-2',
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
        $bell = Html::img('/images/icon/bell.svg', ['class' => 'icon-sm']);
        $html .= Html::a($bell, 'javascript:;', [
            'class' => 'text-light',
            'id' => 'navbarDropdown',
            'role' => 'button',
            'data-toggle' => 'dropdown',
            'aria-haspopup' => 'true',
            'aria-expanded' => 'false'
        ]);

        // first li
        $countNotification = Html::tag('span', 'Notifications');
        $markReadAll = Html::a('Mark all as read', 'javascript:;', ['class' => 'float-right text-light']);
        $firstLiContent = Html::tag('div', $countNotification . $markReadAll, ['class' => 'col-lg-12 col-sm-12 col-12']);
        $firstLiWrapper = Html::tag('div', $firstLiContent, ['class' => 'row']);
        $firstLi = Html::tag('li', $firstLiWrapper, ['class' => 'head text-light']);

        $viewAllLink = Html::a('View All', 'javascript:;', ['class' => 'text-light']);
        $lastLi = Html::tag('li', $viewAllLink, ['class' => 'text-center view-all']);
        $html .= Html::tag('ul', $firstLi . $lastLi, ['class' => 'dropdown-menu']);

        $html .= Html::endTag('div');

        return $html;
    }

    public function registerAssets()
    {
        $this->clientOptions = array_merge([
            'id' => $this->options['id'],
            'url' => Url::to(['push-notification/list']),
            'countUrl' => Url::to(['push-notification/count']),
            'readUrl' => Url::to(['push-notification/read']),
            'readAllUrl' => Url::to(['push-notification/read-all']),
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'pollInterval' => Html::encode($this->pollInterval),
        ], $this->clientOptions);

        $js = 'Notifications(' . Json::encode($this->clientOptions) . ');';
        $view = $this->getView();

        NotificationsAsset::register($view);

        $view->registerJs($js);
    }
}
