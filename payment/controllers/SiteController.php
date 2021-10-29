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
        $form = new \payment\forms\LoginForm(['code' => $code]);
        if (!$form->login()) {
            throw new \yii\web\NotFoundHttpException();
        }
        return $this->redirect('/');
    }
}
