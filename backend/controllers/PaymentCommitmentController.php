<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\data\Pagination;
use yii\helpers\Url;

class PaymentCommitmentController extends Controller
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
        $this->view->params['main_menu_active'] = 'payment_commitment.index';
        $request = Yii::$app->request;
        $condition = [
            'payment_id' => $request->get('payment_id'),
            'object_key' => $request->get('object_key'),
            'customer_id' => $request->get('customer_id'),
            'status' => $request->get('status'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
            'paygate' => $request->get('paygate'),
        ];
        $search = new \backend\forms\FetchPaymentCommitmentForm($condition);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        $approveForm = new \backend\forms\ApprovePaymentCommitmentForm();
        $paymentRealities = $approveForm->fetchPendingReality();
        return $this->render('index', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages,
            'approveForm' => $approveForm,
            'paymentRealities' => $paymentRealities,
        ]);
    }

    public function actionApprove($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\ApprovePaymentCommitmentForm([
            'id' => $id,
            'confirmed_by' => Yii::$app->user->id,
            'allow_variance' => true
        ]);
        if ($model->load($request->post()) && $model->approve()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getFirstErrorMessage()]);
        }
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        $model = new \backend\forms\DeletePaymentCommitmentForm(['id' => $id]);
        if ($model->delete()) {
            return $this->asJson(['status' => true]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $model->getFirstErrorMessage()]);
        }
    }
}
