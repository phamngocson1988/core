<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\SupplierBank;
use supplier\behaviors\UserSupplierBehavior;

class WithdrawController extends Controller
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
        $this->view->params['main_menu_active'] = 'withdraw.index';
        $request = Yii::$app->request;
        $command = SupplierWithdrawRequest::find([
            'supplier_id' => Yii::$app->user->id,
            'status' => $request->get('status', '')
        ]);

        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();
        return $this->render('index', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'withdraw.index';
        $request = Yii::$app->request;
        $model = new SupplierWithdrawRequest(['scenario' => SupplierWithdrawRequest::SCENARIO_CREATE]);
        if ($model->load(Yii::$app->request->post())) {
            $model->supplier_id = Yii::$app->user->id;
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['bank/index']));
                return $this->redirect(Url::to(['withdraw/index']));
            } else {
                $errors = $model->getErrorSummary(true);
                Yii::$app->session->setFlash('error', reset($errors));
            }
        }
        $user = Yii::$app->user->identity;
        $user->attachBehavior('supplier', new UserSupplierBehavior);
        $supplier = $user->supplier;
        $banks = $supplier->banks;
        $bankList = [];
        foreach ($banks as $bank) {
            $bankList[$bank->id] = sprintf("(%s) %s - %s", $bank->bank_code, $bank->account_name, $bank->account_number);
        }
        return $this->render('create', [
            'model' => $model,
            'banks' => $bankList
        ]);
    }

    public function actionCancel($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $request = SupplierWithdrawRequest::findOne($id);
            if (!$request) throw new NotFoundHttpException('Not found');
            if ($request->isRequest()) {
                $request->setScenario(SupplierWithdrawRequest::SCENARIO_CANCEL);
                $request->cancelled_at = date('Y-m-d H:i:s');
                $request->cancelled_by = Yii::$app->user->id;
                $request->status = SupplierWithdrawRequest::STATUS_CANCEL;
                return $this->asJson(['status' => $request->save()]);
            }
            return $this->asJson(['status' => false, 'error' => 'Yêu cầu không hợp lệ']);
        }
    }
}
