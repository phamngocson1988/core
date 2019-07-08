<?php

namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use backend\components\actions\SettingsAction;
use backend\forms\ApplicationSettingForm;
use backend\forms\SocialSettingForm;
use backend\forms\HeaderScriptSettingForm;
use backend\forms\GallerySettingForm;
use backend\forms\PaypalSettingForm;
use backend\forms\AlipaySettingForm;
use backend\forms\SkrillSettingForm;
use backend\forms\ImportSettingForm;
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
            'import' => [
                'class' => SettingsAction::class,
                'modelClass' => ImportSettingForm::class,
                'view' => 'import.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.import']
            ],
            'paypal' => [
                'class' => SettingsAction::class,
                'modelClass' => PaypalSettingForm::class,
                'view' => 'paypal.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.paypal']
            ],
            'alipay' => [
                'class' => SettingsAction::class,
                'modelClass' => AlipaySettingForm::class,
                'view' => 'alipay.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.alipay']
            ],
            'skrill' => [
                'class' => SettingsAction::class,
                'modelClass' => SkrillSettingForm::class,
                'view' => 'skrill.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.skrill']
            ],
            'gallery' => [
                'class' => SettingsAction::class,
                'modelClass' => GallerySettingForm::class,
                'view' => 'gallery.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.gallery'],
                'prepareModel' => function($model) {
                    foreach ($model->attributes() as $attribute) {
                        $value = Yii::$app->settings->get('GallerySettingForm', $attribute);

                        if (!is_null($value)) {
                            $model->{$attribute} = json_decode($value, true);
                        }
                    }
                }, 
                'saveSettings' => function($model) {
                    foreach ($model->toArray() as $key => $value) {
                        Yii::$app->settings->set('GallerySettingForm', $key, json_encode($value));
                    }
                }
            ],
        ];
    }
}