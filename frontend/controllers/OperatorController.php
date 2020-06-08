<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;

use frontend\models\Operator;
use frontend\models\OperatorFavorite;
use frontend\models\OperatorReview;
use frontend\models\Bonus;
use frontend\models\Complain;

class OperatorController extends Controller
{
	public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'actions' => ['index', 'view', 'list-review'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['add-favorite', 'add-review'],
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

    public function actionView($id, $slug) 
    {
        $model = Operator::findOne($id);
        if (!$model) throw new NotFoundHttpException(Yii::t('app', 'operator_not_found'));

        $user = Yii::$app->user->getIdentity();
        $isFavorite = $user && $user->isOperatorFavorite($model->id);
        $isReview = $user && $user->isReview($model->id);
        $reviewForm = new \frontend\forms\AddOperatorReviewForm();
        $bonuses = Bonus::find()->where(['operator_id' => $model->id, 'status' => Bonus::STATUS_ACTIVE])->limit(4)->all();
        $complains = Complain::find()->where(['operator_id' => $model->id])->limit(4)->all();
        return $this->render('view', [
            'model' => $model,
            'isFavorite' => $isFavorite,
            'isReview' => $isReview,
            'user' => $user,
            'reviewForm' => $reviewForm,
            'bonuses' => $bonuses,
            'complains' => $complains,
        ]);
    }

    public function actionAddFavorite()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\AddOperatorFavoriteForm([
            'user_id' => Yii::$app->user->id,
            'operator_id' => $request->get('id')
        ]);
        if ($model->validate() && $model->add()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'add_operator_favorite_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }

    public function actionListReview()
    {
        $request = Yii::$app->request;
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $command = OperatorReview::find()->where(['operator_id' => $request->get('id')])->offset($offset)->limit($limit);
        $models = $command->all();
        $total = $command->count();
        $html = $this->renderPartial('list-review', ['models' => $models]);
        return $this->asJson(['status' => true, 'data' => [
            'items' => $html,
            'total' => $total
        ]]);

    }
    public function actionAddReview()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);

        $model = new \frontend\forms\AddOperatorReviewForm([
            'user_id' => Yii::$app->user->id,
            'operator_id' => $request->get('id')
        ]);
        if ($model->load($request->post()) && $model->validate() && $model->add()) {
            return json_encode(['status' => true, 'data' => ['message' => Yii::t('app', 'add_operator_favorite_success')]]);
        } else {
            $message = $model->getErrorSummary(true);
            $message = reset($message);
            return json_encode(['status' => false, 'errors' => $message]);
        }
    }
}