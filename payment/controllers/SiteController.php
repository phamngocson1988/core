<?php
namespace payment\controllers;
use Yii;


/**
 * Site controller
 */
class SiteController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionLogin()
    {
        $request = Yii::$app->request;
        $code = $request->get('code');
        $token = $request->get('token');
        $form = new \payment\forms\LoginForm(['code' => $code]);
        if (!$form->login()) {
            throw new \yii\web\NotFoundHttpException();
        }
        if ($token) {
            return $this->redirect(['wallet/order', 'token' => $token]);
        }
        return $this->redirect(['wallet/index']);
    }
}
