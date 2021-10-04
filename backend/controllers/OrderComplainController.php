<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\data\Pagination;
use yii\helpers\Url;

use common\models\OrderComplainTemplate;
use backend\models\OrderComplains;

/**
 * OrderComplainController
 */
class OrderComplainController extends Controller
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
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['delete'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'ordercomplain.index';
        $command = OrderComplainTemplate::find()->orderBy(['id' => SORT_ASC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'template' => new OrderComplainTemplate()
        ]);
    }

    public function actionCreate()
    {
        $request = Yii::$app->request;
        $model = new OrderComplainTemplate(['scenario' => OrderComplainTemplate::SCENARIO_CREATE]);
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionEdit($id)
    {
        $request = Yii::$app->request;
        $model = OrderComplainTemplate::findOne($id);
        $model->scenario = OrderComplainTemplate::SCENARIO_EDIT; 
        if ($model->load($request->post()) && $model->save()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = OrderComplainTemplate::findOne($id);
        if ($model && $model->delete()) {
            return $this->renderJson(true, []);
        } else {
            return $this->renderJson(false, [], $model->getErrorSummary(true));
        }
    }

    public function actionList($id)
    {
        $request = Yii::$app->request;
        $models = OrderComplains::find()->where(['order_id' => $id])
        ->with('sender')->all();
        $list = array_map(function($model) {
            $sender = $model->sender;
            $senderName = $sender->name;
            if ($model->isSupplier()) {
                $senderName = Yii::$app->user->can('orderteam') ? $senderName : 'Supplier';
            } elseif ($model->isCustomer()) {
                $senderName = Yii::$app->user->can('saler') ? $senderName : 'Buyer';
            }

            $object = [];
            $object['id'] = $model->id;
            $object['content_type'] = $model->content_type;
            $object['avatar'] = $sender->getAvatarUrl(null, null);
            $object['senderName'] = $senderName;
            $object['content'] = nl2br($model->content);
            $object['created_at'] = \common\components\helpers\TimeElapsed::timeElapsed($model->created_at);
            return $object;
        }, $models);
        return $this->asJson(['list' => $list]);
    }
}
