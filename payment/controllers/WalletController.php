<?php
namespace payment\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use common\components\helpers\StringHelper;

// models
use common\models\Paygate;
use common\models\PaymentTransaction;
use common\models\CurrencySetting;

class WalletController extends \yii\web\Controller
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
        $request = Yii::$app->request;
        $paygates = Paygate::find()->where([
            'status' => Paygate::STATUS_ACTIVE,
        ])->all();

        $user = Yii::$app->user->identity;
    	return $this->render('index', [
            'paygates' => $paygates,
            'user' => $user
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

        $form = new \website\forms\WalletPaymentForm([
            'quantity' => $request->post('quantity', 0),
            'paygate' => $request->post('paygate'),
        ]);

        if ($form->validate()) {
            $calculate = $form->calculate();
            $totalPayment = StringHelper::numberFormat($calculate['totalPayment'], 2);
            $paygate = $form->getPaygateConfig();
            if ($paygate->currency != 'USD') {
                $usdCurrency = CurrencySetting::findOne(['code' => 'USD']);
                $targetCurrency = CurrencySetting::findOne(['code' => $paygate->currency]);
                $otherCurrencyTotal = $usdCurrency->exchangeTo($calculate['totalPayment'], $targetCurrency);
                $otherCurrency = sprintf("%s%s", StringHelper::numberFormat($otherCurrencyTotal, 2), $targetCurrency->getSymbol());
                $totalPayment = sprintf("%s (%s)", $totalPayment, $otherCurrency);

                
            }
            $data = [
                'subTotalPayment' => StringHelper::numberFormat($calculate['subTotalPayment'], 2),
                'transferFee' => StringHelper::numberFormat($calculate['transferFee'], 2),
                'totalPayment' => $totalPayment,
            ];
            return $this->asJson(['status' => true, 'data' => $data]);
        } else {
            return $this->asJson(['status' => false, 'errors' => $form->getErrorSummary(true)]);
        }
    }

    public function actionPurchase()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) throw new BadRequestHttpException("Error Processing Request", 1);
        if (!$request->isPost) throw new BadRequestHttpException("Error Processing Request", 1);
        $user = Yii::$app->user->getIdentity();
        $form = new \website\forms\WalletPaymentForm([
            'quantity' => $request->post('quantity', 0),
            'paygate' => $request->post('paygate'),
        ]);
        $customerName = $request->post('name');

        if ($form->validate() && $trnId = $form->purchase()) {
            $paygate = $form->getPaygate();
            $trn = PaymentTransaction::findOne($trnId);
            $trn->remark = $customerName;
            $trn->save();
            $url = $paygate->createCharge($trn, $user);
            return $this->asJson(['status' => true, 'data' => $trnId, 'url' => $url]);
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