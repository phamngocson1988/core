<?php

namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\controllers\Controller;
use backend\components\actions\SettingsAction;
use backend\forms\ApplicationSettingForm;
use backend\forms\SocialSettingForm;
use backend\forms\HeaderScriptSettingForm;
use backend\forms\GallerySettingForm;
use backend\forms\PaypalSettingForm;
use backend\forms\AlipaySettingForm;
use backend\forms\WechatSettingForm;
use backend\forms\SkrillSettingForm;
use backend\forms\PostalSavingsBankOfChinaSettingForm;
use backend\forms\ImportSettingForm;
use backend\forms\OfflinePaymentSettingForm;
use backend\forms\TopNoticeSettingForm;
use backend\forms\FlashAnnouncementForm;
use backend\forms\WelcomeBonusForm;
use backend\forms\AffiliateProgramForm;
use backend\forms\ReferProgramForm;
use backend\forms\TermsConditionForm;
use backend\forms\EventForm;
use backend\models\PaymentTransaction;
use backend\models\UserWallet;
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
                'modelClass' => ApplicationSettingForm::class,
                'view' => 'application',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
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
            'postal-savings-bank-of-china' => [
                'class' => SettingsAction::class,
                'modelClass' => PostalSavingsBankOfChinaSettingForm::class,
                'view' => 'postal-savings-bank-of-china.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.postal-savings-bank-of-china']
            ],
            'wechat' => [
                'class' => SettingsAction::class,
                'modelClass' => WechatSettingForm::class,
                'view' => 'wechat.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.wechat']
            ],
            'skrill' => [
                'class' => SettingsAction::class,
                'modelClass' => SkrillSettingForm::class,
                'view' => 'skrill.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.skrill']
            ],
            'offline' => [
                'class' => SettingsAction::class,
                'modelClass' => OfflinePaymentSettingForm::class,
                'view' => 'offline.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.offline']
            ],
            'gallery' => [
                'class' => SettingsAction::class,
                'modelClass' => GallerySettingForm::class,
                'view' => 'gallery.tpl',
                'layoutParams' => ['main_menu_active' => 'setting.gallery'],
            ],
            'top_notice' => [
                'class' => SettingsAction::class,
                'modelClass' => TopNoticeSettingForm::class,
                'view' => 'top_notice.php',
                'layoutParams' => ['main_menu_active' => 'setting.top_notice'],
                'on beforeSave' => function ($event) {
                    $form = $event->form;
                    $form->top_notice = serialize($form->top_notice);
                },
            ],
            'flash_announcement' => [
                'class' => SettingsAction::class,
                'modelClass' => FlashAnnouncementForm::class,
                'view' => 'flash_announcement.php',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
            ],
            'welcome_bonus' => [
                'class' => SettingsAction::class,
                'modelClass' => WelcomeBonusForm::class,
                'view' => 'welcome_bonus.php',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
            ],
            'affiliate_program' => [
                'class' => SettingsAction::class,
                'modelClass' => AffiliateProgramForm::class,
                'view' => 'affiliate_program.php',
                'layoutParams' => ['main_menu_active' => 'affiliate.setting'],
            ],
            'refer_program' => [
                'class' => SettingsAction::class,
                'modelClass' => ReferProgramForm::class,
                'view' => 'refer_program.php',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
            ],
            'terms' => [
                'class' => SettingsAction::class,
                'modelClass' => TermsConditionForm::class,
                'view' => 'terms.php',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
            ],
            'event' => [
                'class' => SettingsAction::class,
                'modelClass' => EventForm::class,
                'view' => 'event.php',
                'layoutParams' => ['main_menu_active' => 'setting.application'],
            ],
        ];
    }

    public function actionListOffline()
    {
        $this->view->params['main_menu_active'] = 'setting.offline';
        $request = Yii::$app->request;
        $command = PaymentTransaction::find()->where(['payment_method' => 'offline']);
        $auth_key = $request->get('auth_key');
        if ($auth_key) {
            $command->andWhere(['auth_key' => $auth_key]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('list-offline', [
            'models' => $models,
            'auth_key' => $auth_key,
            'pages' => $pages
        ]);
    }

    
    
}