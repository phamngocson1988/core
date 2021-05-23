<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\data\Pagination;
use supplier\models\SupplierWithdrawRequest;
use supplier\models\SupplierBank;
use supplier\behaviors\UserSupplierBehavior;
use supplier\forms\CreateWithdrawRequestForm;

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
                        'matchCallback' => function ($rule, $action) {
                            return Yii::$app->user->isAdvanceMode();
                        },

                    ],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $this->view->params['main_menu_active'] = 'withdraw.index';
        $request = Yii::$app->request;
        $command = SupplierWithdrawRequest::find()->where([
            'supplier_id' => Yii::$app->user->id,
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
        $model = new CreateWithdrawRequestForm();
        $model->supplier_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->create()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['bank/index']));
                return $this->redirect(Url::to(['withdraw/index']));
            }
        }
        $user = Yii::$app->user->identity;
        $user->attachBehavior('supplier', new UserSupplierBehavior);
        $supplier = $user->supplier;
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionCancel($id)
    {
        $request = Yii::$app->request;
        if ($request->isAjax && $request->isPost) {
            $model = new \supplier\forms\CancelWithdrawRequestForm(['id' => $id, 'supplier_id' => Yii::$app->user->id]);
            if ($model->cancel()) {
                return $this->asJson(['status' => true]);
            } else {
                return $this->asJson(['status' => false, 'validate' => $model->validate(), 'error' => $model->getFirstErrorMessage()]);
            }
        }
        die('Bad request');
    }

    public function actionVerify($id)
    {
        $request = Yii::$app->request;
        $model = new \supplier\forms\VerifyWithdrawRequestForm(['id' => $id, 'supplier_id' => Yii::$app->user->id]);
        if ($request->isAjax) {
            if ($request->isPost) {
                if ($model->load($request->post()) && $model->verify()) {
                    return $this->asJson(['status' => true]);
                } else {
                    return $this->asJson(['status' => false, 'error' => $model->getFirstErrorMessage()]);
                }

            }
            return $this->renderPartial('verify', ['model' => $model]);            
        }
        die('Bad request');
    }
}
