<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use backend\forms\EditProfileForm;
use backend\forms\ChangePasswordForm;
use backend\forms\ChangeAvatarForm;
use backend\models\User;

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

        // Profile form
        $model = new EditProfileForm();
        if ($model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'success'));
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        } else {
            $model->loadData();
        }

        $links = [
            'profile' => Url::to(['profile/index']),
            'change_avatar' => Url::to(['profile/change-avatar']),
            'password' => Url::to(['profile/password']),
            'saler' => Url::to(['profile/saler']),
            'upload_image' => Url::to(['image/ajax-upload']),
        ];
        return $this->render('index', [
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

        // $this->view->registerJsFile('vendor/assets/pages/scripts/profile.min.js', ['depends' => ['\yii\web\JqueryAsset']]);

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
            'saler' => Url::to(['profile/saler']),
            'upload_image' => Url::to(['image/ajax-upload']),
        ];

        return $this->render('password', [
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
        return $this->asJson([
            'status' => $model->change(),
            'data' => [],
            'errors' => $model->getErrors()
        ]);
    }

}
