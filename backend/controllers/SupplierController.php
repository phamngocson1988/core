<?php
namespace backend\controllers;

use Yii;
use yii\data\Pagination;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\BadRequestHttpException;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use backend\forms\FetchSupplierForm;
use backend\forms\FetchSupplierGameForm;
use backend\forms\FetchSupplierWalletForm;
use backend\forms\ReportSupplierBalanceForm;
use backend\models\Supplier;
use backend\models\User;
use backend\models\Order;
use backend\models\OrderSupplier;
use backend\models\SupplierWithdrawRequest;
use backend\models\SupplierGameSuggestion;
use backend\behaviors\UserSupplierBehavior;
use backend\forms\CancelSupplierWithdrawRequest;
use backend\models\SupplierWallet;

class SupplierController extends Controller
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
        $this->view->params['main_menu_active'] = 'supplier.index';
        $request = Yii::$app->request;

        $data = [
            'user_id' => $request->get('user_id'),
            'status' => $request->get('status'),
        ];
        $form = new FetchSupplierForm($data);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $command->orderBy(['created_at' => SORT_DESC]);
        $models = $command->offset($pages->offset)->limit($pages->limit)->all();

        return $this->render('index.php', [
            'models' => $models,
            'search' => $form,
            'pages' => $pages,
        ]);
    }

    public function actionCreate($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = new Supplier();
            $supplier->setScenario(Supplier::SCENARIO_CREATE);
            $supplier->user_id = $id;
            $user->is_supplier = User::IS_SUPPLIER;
            $user->save(false, ['is_supplier']);
            return $this->asJson(['status' => $supplier->save()]);
        }
    }

    public function actionRemove($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            $user->is_supplier = User::IS_NOT_SUPPLIER;
            $user->save(false, ['is_supplier']);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new NotFoundHttpException('Not found');
            return $this->asJson(['status' => $supplier->delete()]);
        }
    }

    public function actionEnable($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new NotFoundHttpException('Not found');
            $supplier->setScenario(Supplier::SCENARIO_EDIT);
            $supplier->status = Supplier::STATUS_ENABLED;
            return $this->asJson(['status' => $supplier->save()]);
        }
    }

    public function actionDisable($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $user = User::findOne($id);
            if (!$user) throw new NotFoundHttpException('Not found');
            $supplier = Supplier::findOne($id);
            if (!$supplier) throw new NotFoundHttpException('Not found');
            $supplier->setScenario(Supplier::SCENARIO_EDIT);
            $supplier->status = Supplier::STATUS_DISABLED;
            return $this->asJson(['status' => $supplier->save()]);
        }
    }

    public function actionGame($id)
    {
        $request = Yii::$app->request;
        $this->view->params['main_menu_active'] = 'supplier.index';
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException('Not found');
        $user->attachBehavior('supplier', new UserSupplierBehavior);
        if (!$user->isSupplier()) throw new NotFoundHttpException('Not found');
        $command = $user->getSupplierGames();
        $command->with('game');
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();
        return $this->render('game', [
            'models' => $models,
            'pages' => $pages,
            'supplier' => $user
        ]); 
    }

    public function actionWithdraw()
    {
        $this->view->params['main_menu_active'] = 'supplier.withdraw';
        $request = Yii::$app->request;
        $command = SupplierWithdrawRequest::find()
        // ->where(['supplier_id' => $request->get('supplier_id', '')])
        ->andWhere(["IN", 'status', [
            SupplierWithdrawRequest::STATUS_REQUEST,
            SupplierWithdrawRequest::STATUS_APPROVE,
            SupplierWithdrawRequest::STATUS_DONE    
        ]])
        ;

        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['created_at' => SORT_DESC])
                            ->all();
        return $this->render('withdraw', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionCancelWithdraw($id)
    {
        $request = Yii::$app->request;
        $model = new CancelSupplierWithdrawRequest(['id' => $id]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->cancel()) {
                return $this->asJson(['status' => true]);
            } else {
                $errors = $model->getErrorSummary(false);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'error' => $error]);
            }
        }
        return $this->renderPartial('_cancel_withdraw.php', [
            'model' => $model
        ]);
    }

    public function actionApproveWithdraw($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $model = SupplierWithdrawRequest::findOne($id);
            if (!$model) throw new NotFoundHttpException('Not found');
            if ($model->isRequest()) {
                $model->setScenario(SupplierWithdrawRequest::SCENARIO_APPROVE);
                $model->approved_at = date('Y-m-d H:i:s');
                $model->approved_by = Yii::$app->user->id;
                $model->status = SupplierWithdrawRequest::STATUS_APPROVE;
                return $this->asJson(['status' => $model->save()]);
            }
            return $this->asJson(['status' => false, 'error' => 'Yêu cầu không hợp lệ']);
        }
    }

    public function actionDoneWithdraw($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $model = SupplierWithdrawRequest::findOne($id);
            if (!$model) throw new NotFoundHttpException('Not found');
            if ($model->isApprove()) {
                $model->setScenario(SupplierWithdrawRequest::SCENARIO_DONE);
                $model->done_at = date('Y-m-d H:i:s');
                $model->done_by = Yii::$app->user->id;
                $model->status = SupplierWithdrawRequest::STATUS_DONE;
                $model->on(SupplierWithdrawRequest::EVENT_AFTER_UPDATE , function($event) {
                    $withdraw = $event->sender;
                    $supplier = $withdraw->supplier;
                    $supplier->withdraw($withdraw->amount, 'withdraw', $withdraw->id, sprintf("Pay for withdraw request #%s", $withdraw->id));
                });
                return $this->asJson(['status' => $model->save()]);
            }
            return $this->asJson(['status' => false, 'error' => 'Yêu cầu không hợp lệ']);
        }
    }

    public function actionEvidenceWithdraw($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $model = SupplierWithdrawRequest::findOne($id);
            $model->setScenario(SupplierWithdrawRequest::SCENARIO_EVIDENCE);
            $files = Yii::$app->file->upload('evidence', "supplier/evidence/$id", true);
            $inputFile = reset($files);
            $model->evidence = $inputFile;
            $model->save();
            return $this->redirect($request->getReferrer());
        }
    }

    public function actionRemoveEvidenceWithdraw($id)
    {
        $request = Yii::$app->request;
        if ($request->isPost) {
            $model = SupplierWithdrawRequest::findOne($id);
            $model->setScenario(SupplierWithdrawRequest::SCENARIO_EVIDENCE);
            $model->evidence = '';
            $model->save();
            return $this->redirect($request->getReferrer());
        }
    }

    public function actionSuggest()
    {
        $this->view->params['main_menu_active'] = 'supplier.suggest';
        $request = Yii::$app->request;
        $command = SupplierGameSuggestion::find();
        $command->orderBy(['created_at' => SORT_DESC]);
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->all();

        return $this->render('suggest.php', [
            'models' => $models,
            'pages' => $pages,
        ]);
    }

    public function actionDeleteSuggest($id)
    {
        $request = Yii::$app->request;
        $model = SupplierGameSuggestion::findOne($id);
        if (!$model) throw new Exception("Not found", 1);
        return $this->asJson(['status' => $model->delete()]);
        
    }

    public function actionWallet($id)
    {
        $supplier = Supplier::findOne($id);
        $command = SupplierWallet::find()
        ->where(["supplier_id" => $id])
        ->andWhere(["type" => SupplierWallet::TYPE_INPUT])
        ->andWhere(["status" => SupplierWallet::STATUS_COMPLETED]);
        $totalAmount = $command->sum("amount");
        $orders = $command->all();

        // completed requests
        $requestCommand = SupplierWithdrawRequest::find()
        ->where(['supplier_id' => $id])
        ->andWhere(['status' => SupplierWithdrawRequest::STATUS_DONE]);
        $totalWithdraw = $requestCommand->sum('amount');
        $requests = $requestCommand->all();
        return $this->renderPartial('wallet', [
            'orders' => $orders,
            'totalAmount' => $totalAmount,
            'totalWithdraw' => $totalWithdraw,
            'requests' => $requests,
            'supplier' => $supplier
        ]);
    }

    public function actionBalance()
    {
        $this->view->params['main_menu_active'] = 'supplier.balance';
        $request = Yii::$app->request;
        $form = new ReportSupplierBalanceForm([
            'supplier_id' => $request->get('supplier_id'),
            'report_from' => $request->get('report_from'),
            'report_to' => $request->get('report_to'),
        ]);
        $models = $form->getReport();

        return $this->render('balance.php', [
            'models' => $models,
            'search' => $form,
            'pages' => $form->getPage(),
        ]);
    }
}
