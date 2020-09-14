<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use supplier\forms\EditProfileForm;
use supplier\forms\ChangePasswordForm;
use supplier\forms\ChangeAvatarForm;
use supplier\forms\ChangeAdvancePasswordForm;

class ProfileController extends Controller
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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->isAdvanceMode();
                        },

                    ],
                ],
            ],
        ];
    }

    /**
     *
     * @return string
     */
    public function actionIndex()
    {
        $request = Yii::$app->request;
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $this->view->registerCssFile('vendor/assets/pages/css/profile.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);

        // $this->view->registerJsFile('vendor/assets/pages/scripts/profile.min.js', ['depends' => ['\yii\web\JqueryAsset']]);

        // Profile form
        $model = new EditProfileForm();
        if ($model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrorSummary(true));
            }
        } else {
            $model->loadData();
        }

        $links = [
            'profile' => Url::to(['profile/index']),
            'change_avatar' => Url::to(['profile/change-avatar']),
            'password' => Url::to(['profile/password']),
            'advance_password' => Url::to(['profile/advance-password']),
            'saler' => Url::to(['profile/saler']),
            'upload_image' => Url::to(['image/ajax-upload']),
        ];
        return $this->render('index.tpl', [
            'model' => $model,
            'links' => $links
        ]);
    }


    public function actionPassword()
    {
        $request = Yii::$app->request;
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $this->view->registerCssFile('vendor/assets/pages/css/profile.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);

        $post = $request->post();
        $model = new ChangePasswordForm();
        
        if ($model->load($post) && $model->change()) {
            // Reset form change password
            $model = new ChangePasswordForm();
        }

        $links = [
            'profile' => Url::to(['profile/index']),
            'change_avatar' => Url::to(['profile/change-avatar']),
            'password' => Url::to(['profile/password']),
            'advance_password' => Url::to(['profile/advance-password']),
            'saler' => Url::to(['profile/saler']),
            'upload_image' => Url::to(['image/ajax-upload']),
        ];

        return $this->render('password.tpl', [
            'model' => $model,
            'user' => Yii::$app->user->getIdentity(),
            'links' => $links
        ]);
    }

    public function actionAdvancePassword()
    {
        $request = Yii::$app->request;
        $this->view->params['body_class'] = 'page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white';
        $this->view->registerCssFile('vendor/assets/pages/css/profile.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerCssFile('vendor/assets/global/plugins/bootstrap-fileinput/bootstrap-fileinput.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);

        $post = $request->post();
        $model = new ChangeAdvancePasswordForm();
        
        if ($model->load($post) && $model->change()) {
            // Reset form change password
            $model = new ChangeAdvancePasswordForm();
        }

        $links = [
            'profile' => Url::to(['profile/index']),
            'change_avatar' => Url::to(['profile/change-avatar']),
            'password' => Url::to(['profile/password']),
            'advance_password' => Url::to(['profile/advance-password']),
            'saler' => Url::to(['profile/saler']),
            'upload_image' => Url::to(['image/ajax-upload']),
        ];

        return $this->render('advance_password.tpl', [
            'model' => $model,
            'user' => Yii::$app->user->getIdentity(),
            'links' => $links
        ]);
    }

    public function actionChangeAvatar()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return;
        }

        $imageId = $request->post('image_id');
        $model = new ChangeAvatarForm([
            'image_id' => $imageId
        ]);
        
        return $this->renderJson($model->change(), [], $model->getErrors());
    }

}
