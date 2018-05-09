<?php

namespace client\controllers;

use yii\web\Controller;
use client\components\actions\SettingsAction;
use client\forms\ApplicationSettingForm;
use client\forms\SocialSettingForm;
use client\forms\HeaderScriptSettingForm;

/**
 * Class SettingController
 *
 * @package client\controllers
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
            'social' => [
                'class' => SettingsAction::class,
                'modelClass' => SocialSettingForm::class,
                'view' => 'social.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.social']
            ],
            'script' => [
                'class' => SettingsAction::class,
                'modelClass' => HeaderScriptSettingForm::class,
                'view' => 'script.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.script']
            ],
        ];
    }
}