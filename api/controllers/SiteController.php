<?php
namespace api\controllers;

use Yii;
use api\forms\LoginForm;

/**
 * Site controller
 */
class SiteController extends ActiveController
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        unset($behaviors['authenticator']);
        return $behaviors;
    }

    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        $model = new LoginForm($post);

        if ($model->login()) {
            return $this->asJson(['result' => true, 'user' => $model->getUser()]);
        } else {
            return $this->asJson(['result' => false, 'error' => $model->getErrors()]);
        }
    }
}
