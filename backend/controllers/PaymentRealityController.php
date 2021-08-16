<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

class PaymentRealityController extends Controller
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
        $this->view->params['main_menu_active'] = 'payment_reality.index';
        $request = Yii::$app->request;
        $condition = [
            'id' => $request->get('id'),
            'object_key' => $request->get('object_key'),
            'customer_id' => $request->get('customer_id'),
            'payment_id' => $request->get('payment_id'),
            'payer' => $request->get('payer'),
            'status' => $request->get('status'),
            'date_type' => $request->get('date_type'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'paygate' => $request->get('paygate'),
            'confirmed_by' => $request->get('confirmed_by'),
        ];
        $search = new \backend\forms\FetchPaymentRealityForm($condition);
        $mode = $request->get('mode');
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'hoa-don-nhan-tien.xls';
            return $search->export($fileName);
        }
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $deleteForm = new \backend\forms\DeletePaymentRealityForm();
        return $this->render('index', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages,
            'deleteForm' => $deleteForm
        ]);
    }

    public function actionDeletedItems()
    {
        $this->view->params['main_menu_active'] = 'payment_reality.index';
        $request = Yii::$app->request;
        $condition = [
            'id' => $request->get('id'),
            'object_key' => $request->get('object_key'),
            'customer_id' => $request->get('customer_id'),
            'payment_id' => $request->get('payment_id'),
            'payer' => $request->get('payer'),
            'date_type' => $request->get('date_type'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'paygate' => $request->get('paygate'),
        ];
        $condition['status'] = \common\models\PaymentReality::STATUS_DELETED;
        $search = new \backend\forms\FetchPaymentRealityForm($condition);
        $mode = $request->get('mode');
        if ($mode === 'export') {
            $fileName = date('YmdHis') . 'hoa-don-nhan-tien.xls';
            return $search->export($fileName);
        }
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('deleted', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'payment_reality.index';
        $request = Yii::$app->request;
        $model = new \backend\forms\CreatePaymentRealityForm();
        if ($model->load($request->post())) {
            if ($model->validate() && $model->create()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['payment-reality/index']));
                return $this->redirect($ref);
            } else {
                Yii::$app->session->setFlash('error', $model->getFirstErrorMessage());
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\DeletePaymentRealityForm(['id' => $id]);
        if ($model->load($request->post()) && $model->delete()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getFirstErrorMessage()]);
        }
    }

    public function actionAjaxView($id) 
    {   
        $model = \common\models\PaymentReality::find()->asArray()->where(['id' => $id])->one();
        return $this->asJson($model);
    }
}
