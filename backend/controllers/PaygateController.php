<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;
use backend\models\Paygate;

class PaygateController extends Controller
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
        $this->view->params['main_menu_active'] = 'paygate.index';
        $request = Yii::$app->request;
        $models = Paygate::find()->all();
        return $this->render('index', [
            'models' => $models,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'paygate.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreatePaygateForm();
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['paygate/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionEdit($id)
    {
        $this->view->params['main_menu_active'] = 'paygate.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\EditPaygateForm(['id' => $id]);
        if ($model->load($request->post())) {
            if ($model->validate() && $model->update()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['paygate/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getErrors());
            }
        } else {
            $model->loadData();
        }

        return $this->render('edit', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $paygate = Paygate::findOne($id);
        if ($paygate) {
            $paygate->delete();
        }
        $name = $paygate ? $paygate->name : '';
        return $this->asJson(['status' => true, 'data' => ['message' => sprintf("Bạn đã xoá cổng thanh toán %s thành công", $name)]]);
    }
}
