<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

// models
use website\models\Paygate;
use website\models\PaymentTransaction;

class WalletController extends Controller
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
        $this->view->params['main_menu_active'] = 'wallet.index';
        $paygates = Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();

    	return $this->render('index', [
            'paygates' => $paygates
        ]);
    }

    public function actionView() 
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $payment = PaymentTransaction::findOne($id);
        if (!$payment) {
            return $this->asJson(['status' => false, 'errors' => 'Transaction is not found']);
        }
        if ($payment->user_id != Yii::$app->user->id) {
            return $this->asJson(['status' => false, 'errors' => 'Transaction is not found']);
        }
        $paygate = Paygate::find()->where(['identifier' => $payment->payment_method])->one();
        $model = new \website\forms\UpdateTransactionForm(['id' => $id]);
        $model->loadData();
        return $this->asJson(['status' => true, 'data' => $this->renderPartial('view', [
            'payment' => $payment,
            'paygate' => $paygate,
            'model' => $model,
        ])]);
    }

    public function actionCalculate() 
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'errors' => 'You need to login']);

        $form = new \website\forms\WalletPaymentForm([
            'quantity' => $request->post('quantity', 0),
            'voucher' => $request->post('voucher', ''),
            'paygate' => $request->post('paygate'),
        ]);

        if ($form->validate()) {
            return $this->asJson(['status' => true, 'data' => $form->calculate()]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $form->getErrorSummary(true)]);
        }
    }

    public function actionPurchase()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'errors' => 'You need to login']);

        $form = new \website\forms\WalletPaymentForm([
            'quantity' => $request->post('quantity', 0),
            'voucher' => $request->post('voucher', ''),
            'paygate' => $request->post('paygate'),
        ]);

        if ($form->validate() && $trnId = $form->purchase()) {
            return $this->asJson(['status' => true, 'data' => $trnId]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $form->getErrorSummary(true)]);
        }
    }

    public function actionUpdate()
    {
        $request = Yii::$app->request;
        $id = $request->get('id');
        $model = new \website\forms\UpdateTransactionForm(['id' => $id]);
        if ($model->load($request->post())) {
            $files = Yii::$app->file->upload('evidence', "evidence/$id", true);
            if ($files) {
                $inputFile = reset($files);
                $model->evidence = $inputFile;
            }
            if ($model->validate() && $model->update()) {
                return $this->asJson(['status' => true, 'message' => sprintf("You have updated transaction #%s successfully.", $id)]);
            } else {
                $errors = $model->getErrorSummary(true);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
    }
}