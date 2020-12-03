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
use yii\data\Pagination;

use frontend\models\Operator;
use frontend\models\OperatorFavorite;
use frontend\models\OperatorReview;

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
        $form = new \frontend\forms\FetchOperatorForm();
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $operators = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $total = $command->count();
        return $this->render('index', [
            'operators' => $operators,
            'pages' => $pages,
            'total' => $total,
        ]);
    }

    public function actionView($id, $slug) 
    {
        $model = Operator::findOne($id);
        if (!$model) throw new NotFoundHttpException(Yii::t('app', 'operator_not_found'));

        $user = Yii::$app->user->getIdentity();
        $isFavorite = $user && $user->isOperatorFavorite($model->id);
        $isReview = $user && $user->isReview($model->id);
        $canManageOperator = $user && $user->isOperatorStaffOf($model->id);
        $reviewForm = new \frontend\forms\AddOperatorReviewForm();

        return $this->render('view', [
            'model' => $model,
            'isFavorite' => $isFavorite,
            'isReview' => $isReview,
            'user' => $user,
            'reviewForm' => $reviewForm,
            'canManageOperator' => $canManageOperator
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
        if ($request->get('sort') == 'date') {
            $type = $request->get('type', 'asc') == 'asc' ? SORT_ASC : SORT_DESC;
            $command->orderBy(['created_at' => $type]);
        }
        if ($request->get('sort') == 'rate') {
            $type = $request->get('type', 'asc') == 'asc' ? SORT_ASC : SORT_DESC;
            $command->orderBy(['star' => $type]);
        }

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