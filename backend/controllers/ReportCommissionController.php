<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\User;


class ReportCommissionController extends Controller
{
    /**
     * @inheritdoc
     */
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
        $this->view->params['main_menu_active'] = 'report.commission.index';
        $request = Yii::$app->request;
        $form = new \backend\forms\ReportCommissionForm([
            'user_ids' => $request->get('user_ids', [Yii::$app->user->id]),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'game_id' => $request->get('game_id'),
        ]);
        $form->run();
        return $this->render('index', [
            'search' => $form,
        ]);
        return $this->render('index');
    }

    public function actionDetail()
    {
        $this->view->params['main_menu_active'] = 'report.commission.index';
        $request = Yii::$app->request;
        $type = $request->get('type');

        $form = new \backend\forms\ReportCommissionForm([
            'user_ids' => [$request->get('user_id')],
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ]);
        $form->run();
        $data = $form->getData();
        $data = array_filter($data, function($row) use ($type) {
            return $row['commission_type'] === $type;
        });
        return $this->render('detail', ['data' => $data]);
    }

    public function actionOrderDetail($id, $type, $role)
    {
        $order = \backend\models\Order::findOne($id);
        return $this->renderPartial('_order-detail', ['order' => $order, 'id' => $id, 'type' => $type, 'role' => $role]);
    }
}
