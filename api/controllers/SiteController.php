<?php
namespace api\controllers;

use Yii;
use api\forms\LoginForm;

/**
 * Site controller
 */
class SiteController extends ActiveController
{
    public function actionLogin()
    {
        $post = Yii::$app->request->post();
        $model = new LoginForm($post);

        if ($model->login()) {
            $user = $model->getUser();
            $authKey = $user->getAuthKey();
            return $this->asJson([
                'result' => true, 
                'user' => $user->exportData(),
                'auth_key' => $authKey,
            ]);
        } else {
            return $this->asJson(['result' => false, 'error' => $model->getErrors()]);
        }
    }
}
