<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\OrderLog;

class OrderLogController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['accounting', 'saler', 'admin'],
                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'order.log';
        $request = Yii::$app->request;
        $command = OrderLog::find();
        $command->with('user');
        $command->orderBy(['id' => SORT_DESC]);
        $order_id = $request->get('order_id');
        if ($order_id) {
            $command->andWhere(['order_id' => $order_id]);
        }
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
            'order_id' => $order_id
        ]);
    }
}
