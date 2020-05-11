<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\components\actions\SettingsAction;
use backend\forms\ApplicationSettingForm;
use yii\data\Pagination;

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
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'application' => [
                'class' => SettingsAction::class,
                'sectionName' => 'application',
                'modelClass' => ApplicationSettingForm::class,
                'view' => 'application',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
            ],
        ];
    }
}