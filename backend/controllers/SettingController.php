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
use backend\forms\OfflinePaymentSettingForm;
use backend\forms\TopNoticeSettingForm;
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
    public function actions()
    {
        return [
            'application' => [
                'class' => SettingsAction::class,
                'modelClass' => ApplicationSettingForm::class,
                'view' => 'application.tpl',
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

    public function actionPayOffline($id) 
    {
        $request = Yii::$app->request;
        $transaction = PaymentTransaction::findOne($id);
        $transaction->setScenario(PaymentTransaction::SCENARIO_CONFIRM_OFFLINE_PAYMENT);
        if (!$transaction) return $this->asJson(['status' => false, 'errors' => 'Không tim thấy giao dịch']);
        if ($transaction->status == PaymentTransaction::STATUS_COMPLETED) return $this->asJson(['status' => false, 'errors' => 'Giao dịch đã được thanh toán']);
        if ($transaction->load($request->post()) && $transaction->save()) {
            $user = $transaction->user;
            $wallet = new UserWallet();
            $wallet->coin = $transaction->total_coin;
            $wallet->balance = $user->getWalletAmount() + $wallet->coin;
            $wallet->type = UserWallet::TYPE_INPUT;
            $wallet->description = "Transaction #$transaction->auth_key";
            $wallet->ref_name = PaymentTransaction::className();
            $wallet->ref_key = $transaction->auth_key;
            $wallet->created_by = Yii::$app->user->id;
            $wallet->user_id = $user->id;
            $wallet->status = UserWallet::STATUS_COMPLETED;
            $wallet->payment_at = date('Y-m-d H:i:s');
            $wallet->save();
            return $this->asJson(['status' => true]);
        }
        else {
            $errors = $transaction->getErrorSummary(true);
            return $this->asJson(['status' => false, 'errors' => reset($errors)]);
        }
    }

    public function actionDeleteOffline($id)
    {
        $request = Yii::$app->request;
        $transaction = PaymentTransaction::findOne($id);
        if (!$transaction) return $this->asJson(['status' => false, 'errors' => 'Không tim thấy giao dịch']);
        if ($transaction->status == PaymentTransaction::STATUS_COMPLETED) return $this->asJson(['status' => false, 'errors' => 'Không thể xóa giao dịch']);
        if ($transaction->delete()) return $this->asJson(['status' => true]);
        else {
            $errors = $transaction->getErrorSummary(true);
            return $this->asJson(['status' => false, 'errors' => reset($errors)]);
        }
    }
    
}