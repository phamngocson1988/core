<?php

namespace client\components\actions;

use Yii;
use yii\helpers\ArrayHelper;
use yii2mod\settings\actions\SettingsAction as BaseSettingsAction;

/**
 * Class SettingsAction
 *
 * @package yii2mod\settings\actions
 */
class SettingsAction extends BaseSettingsAction
{
    public $layoutParams = [];

    public function run()
    {
        $this->controller->view->params = ArrayHelper::merge($this->controller->view->params, $this->layoutParams);
        return parent::run();
    }
}
