<?php
namespace website\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class ReferralController extends Controller
{
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

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'referral.index';
        $user = Yii::$app->user->getIdentity();
        $model = new \website\forms\ReferralInvitationForm();
        return $this->render('index', [
            'user' => $user,
            'model' => $model,
        ]);
    }

    public function actionInvite()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \website\forms\ReferralInvitationForm([
            'user_id' => Yii::$app->user->id
        ]);
        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->send()) {
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message]);
        }
    }
}