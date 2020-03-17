<?php

namespace backend\controllers;

use backend\components\actions\SettingsAction;
use backend\forms\ApplicationSettingForm;

/**
 * Class SettingController
 *
 * @package backend\controllers
 */
class SettingController extends Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'application' => [
                'class' => SettingsAction::class,
                'modelClass' => ApplicationSettingForm::class,
                'view' => 'application.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.application']
            ],
        ];
    }
}