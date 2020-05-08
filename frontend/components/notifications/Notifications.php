<?php

namespace frontend\components\notifications;

use Yii;
use webzop\notifications\widgets\Notifications as BaseNotifications;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\Json;
use yii\helpers\ArrayHelper;
use backend\components\notifications\NotificationsAsset;

class Notifications extends BaseNotifications
{
    public $options = [
        'class' => 'dropdown dropdown-extended dropdown-notification nav-notifications',
        'id' => 'header_notification_bar',
    ];

    public $countOptions = [
        'tag' => 'span',
        'class' => 'badge badge-default',
    ];

    protected function renderNavbarItem()
    {
        // Start notifications
        $html  = Html::beginTag('li', $this->options);
        $html .= Html::beginTag('a', [
            'href' => 'javascript:;', 
            'class' => 'dropdown-toggle', 
            'data-toggle' => 'dropdown', 
            'data-hover' => 'dropdown', 
            'data-close-others' => 'true'
        ]);
        $html .= Html::tag('i', '', ['class' => 'icon-bell']);

        $count = self::getCountUnseen();
        $countOptions = array_merge([
            'tag' => 'span',
            'data-count' => $count,
        ], $this->countOptions);
        Html::addCssClass($countOptions, 'label label-warning navbar-badge notifications-count');
        if(!$count){
            $countOptions['style'] = 'display: none;';
        }
        $countTag = ArrayHelper::remove($countOptions, 'tag', 'span');
        $html .= Html::tag($countTag, $count, $countOptions);
        $html .= Html::endTag('a');
        
        // Dropdown
        $html .= Html::begintag('ul', ['class' => 'dropdown-menu']);
        $headerCountNotification = Html::tag('span', sprintf("%s", number_format($count)), ['class' => 'bold header-notifications-count']);
        $header = Html::tag('h3', $headerCountNotification . ' tin mới');
        $header .= Html::a(Yii::t('modules/notifications', 'Đánh dấu đã đọc'), '#', ['class' => 'read-all']);
        $html .= Html::tag('li', $header, ['class' => 'external']);

        $html .= Html::begintag('li');
        $html .= Html::begintag('div', ['class' => 'slimScrollDiv', 'style' => 'position: relative; overflow: hidden; width: auto; height: 250px;']);

        $html .= Html::begintag('ul', ['class' => 'dropdown-menu-list scroller notifications-list', 'style' => 'height: 250px; overflow: hidden; width: auto;', 'data-handle-color' => '#637283', 'data-initialized' => '1']);
        $html .= Html::endTag('ul');

        $html .= Html::tag('div', '', ['class' => 'slimScrollBar', 'style' => 'background: rgb(99, 114, 131); width: 7px; position: absolute; top: 0px; opacity: 0.4; display: block; border-radius: 7px; z-index: 99; right: 1px;']);
        $html .= Html::tag('div', '', ['class' => 'slimScrollRail', 'style' => 'width: 7px; height: 100%; position: absolute; top: 0px; display: none; border-radius: 7px; background: rgb(234, 234, 234); opacity: 0.2; z-index: 90; right: 1px;']);
        $html .= Html::endTag('div');
        $html .= Html::endTag('li');

        $html .= Html::endTag('ul');


        $html .= Html::endTag('li');

        return $html;
    }

    public function registerAssets()
    {
        $this->clientOptions = array_merge([
            'id' => $this->options['id'],
            'url' => Url::to(['/notifications/default/list']),
            'countUrl' => Url::to(['/notifications/default/count']),
            'readUrl' => Url::to(['/notifications/default/read']),
            'readAllUrl' => Url::to(['/notifications/default/read-all']),
            'xhrTimeout' => Html::encode($this->xhrTimeout),
            'pollInterval' => Html::encode($this->pollInterval),
        ], $this->clientOptions);

        $js = 'Notifications(' . Json::encode($this->clientOptions) . ');';
        $view = $this->getView();

        NotificationsAsset::register($view);

        $view->registerJs($js);
    }
}
