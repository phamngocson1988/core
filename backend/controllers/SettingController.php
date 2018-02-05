<?php

namespace backend\controllers;

use yii\web\Controller;
use backend\components\actions\SettingsAction;
use backend\forms\ApplicationSettingForm;
use backend\forms\BankSettingForm;
use backend\forms\SocialSettingForm;
use backend\forms\HeaderScriptSettingForm;

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
            'bank' => [
                'class' => SettingsAction::class,
                'modelClass' => BankSettingForm::class,
                'view' => 'bank.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.bank']
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