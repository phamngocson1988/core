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
        $model = new SupplierBank(['scenario' => SupplierBank::SCENARIO_CREATE]);
        $model->supplier_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post())) {
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Success!');
                $ref = $request->get('ref', Url::to(['bank/index']));
                return $this->redirect(Url::to(['bank/index']));
            }
        }
        $banks = Bank::find()->all();
        $bankList = [];
        foreach ($banks as $bank) {
            $bankList[$bank->code] = sprintf("(%s) %s", $bank->code, $bank->short_name);
        }
        return $this->render('create', [
            'model' => $model,
            'banks' => $bankList
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
}
