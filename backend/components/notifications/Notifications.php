<?php

namespace backend\components\notifications;

use Yii;
use webzop\notifications\widgets\Notifications as BaseNotifications;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

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
        $html .= Html::begintag('div', ['class' => 'dropdown-menu']);
        $header = Html::a(Yii::t('modules/notifications', 'Mark all as read'), '#', ['class' => 'read-all pull-right']);
        $header .= Yii::t('modules/notifications', 'Notifications');
        $html .= Html::tag('div', $header, ['class' => 'header']);

        $html .= Html::begintag('div', ['class' => 'notifications-list']);
        //$html .= Html::tag('div', '<span class="ajax-loader"></span>', ['class' => 'loading-row']);
        $html .= Html::tag('div', Html::tag('span', Yii::t('modules/notifications', 'There are no notifications to show'), ['style' => 'display: none;']), ['class' => 'empty-row']);
        $html .= Html::endTag('div');

        $footer = Html::a(Yii::t('modules/notifications', 'View all'), ['/notifications/default/index']);
        $html .= Html::tag('div', $footer, ['class' => 'footer']);
        $html .= Html::endTag('div');
        $html .= Html::endTag('li');

        return $html;
    }
}
