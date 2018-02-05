<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use backend\forms\EditProfileForm;
use backend\forms\ChangePasswordForm;
use backend\forms\ChangeAvatarForm;

/**
 * ProfileController
 */
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

        // $this->view->registerJsFile('vendor/assets/pages/scripts/profile.min.js', ['depends' => ['\yii\web\JqueryAsset']]);

        // Profile form
        $model = new EditProfileForm();
        if ($model->load($request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        } else {
            $model->loadData();
        }

        $links = [
            'change_avatar' => Url::to(['profile/change-avatar']),
            'password' => Url::to(['profile/password']),
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

        // $this->view->registerJsFile('vendor/assets/pages/scripts/profile.min.js', ['depends' => ['\yii\web\JqueryAsset']]);

        $post = $request->post();
        $model = new ChangePasswordForm();
        
        if ($model->load($post) && $model->change()) {
            // Reset form change password
            $model = new ChangePasswordForm();
        }

        $links = [
            'change_avatar' => Url::to(['profile/change-avatar']),
            'profile' => Url::to(['profile/index']),
            'upload_image' => Url::to(['image/ajax-upload']),
        ];

        return $this->render('password.tpl', [
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
