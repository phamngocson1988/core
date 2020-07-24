<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\data\Pagination;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

// form
use website\forms\UpdateSecureProfileForm;
use website\models\GameSubscriber;

class UserController extends Controller
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

    }

    public function actionUpdateSecureProfile()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $model = new UpdateSecureProfileForm();
        if ($model->load(Yii::$app->request->post()) && $model->update()) {
            return json_encode(['status' => true, 'user_id' => Yii::$app->user->id]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'user_id' => Yii::$app->user->id, 'errors' => $message, 'model' => 'asdfasdf']);
        }
    }

    public function actionSubscribe()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $gameId = $request->post('game_id');
        $userId = Yii::$app->user->id;
        $exists = GameSubscriber::find(['user_id' => $userId, 'game_id' => $gameId])->exists();
        if (!$exists) {
            $subscriber = new GameSubscriber();
            $subscriber->user_id = $userId;
            $subscriber->game_id = $gameId;
            return $this->asJson(['status' => $subscriber->save()]);
        }
        return $this->asJson(['status' => true]);
    }

    public function actionUnsubscribe()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);

        $gameId = $request->post('game_id');
        $userId = Yii::$app->user->id;
        $subscriber = GameSubscriber::find(['user_id' => $userId, 'game_id' => $gameId])->one();
        if ($subscriber) {
            return $this->asJson(['status' => $subscriber->delete()]);
        }
        return $this->asJson(['status' => true]);
    }

}