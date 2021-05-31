<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use supplier\models\Bank;
use supplier\models\SupplierBank;
use supplier\behaviors\UserSupplierBehavior;

class BankController extends Controller
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
        $this->view->params['main_menu_active'] = 'bank.index';
        $user = Yii::$app->user->getIdentity();
        $user->attachBehavior('supplier', new UserSupplierBehavior);
        $supplier = $user->supplier;
        $supplierBanks = $supplier->banks;
        $banks = Bank::find()->all();
        return $this->render('index', [
            'models' => $supplierBanks,
            'banks' => $banks
        ]);
    }

    public function actionCreate()
    {
        $this->view->params['main_menu_active'] = 'bank.index';
        $request = Yii::$app->request;
        $model = new \supplier\forms\CreateSupplierBankForm();
        $model->supplier_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            $newBank = $model->create();
            if ($newBank) {
                Yii::$app->session->setFlash('success', 'Success!');
                return $this->redirect(Url::to(['bank/verify', 'id' => $newBank->id]));
            }
        }
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    public function actionDelete($id)
    {
        $request = Yii::$app->request;
        if( $request->isAjax) {
            $bank = SupplierBank::findOne($id);
            if (!$bank) throw new NotFoundHttpException('Not found');
            return $this->asJson(['status' => $bank->delete()]);
        }
    }

    public function actionVerify($id)
    {
        $this->view->params['main_menu_active'] = 'bank.index';
        $request = Yii::$app->request;
        $model = new \supplier\forms\VerifySupplierBankForm([
            'id' => $id, 
            'supplier_id' => Yii::$app->user->id,
            'scenario' => \supplier\forms\VerifySupplierBankForm::SCENARIO_VERIFY
        ]);
        if ($request->isPost) {
            if ($model->load($request->post()) && $model->verify()) {
                return $this->redirect(['bank/index']);
            }
        }
        return $this->render('verify', ['model' => $model]);            
    }

    public function actionSendValidateCode($id)
    {
        $request = Yii::$app->request;
        $model = new \supplier\forms\VerifySupplierBankForm([
            'id' => $id, 
            'supplier_id' => Yii::$app->user->id,
            'scenario' => \supplier\forms\VerifySupplierBankForm::SCENARIO_SEND
        ]);
        if ($request->isAjax) {
            if ($model->send()) {
                return $this->asJson(['status' => true]);
            } else {
                return $this->asJson(['status' => false, 'error' => $model->getFirstErrorMessage()]);
            }
        }
        die('Bad request');
    }
}
