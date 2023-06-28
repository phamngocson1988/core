<?php
namespace api\controllers;

use Yii;
use yii\rest\Controller;
use yii\filters\auth\HttpBearerAuth;

// models
use common\components\helpers\StringHelper;
use api\models\Paygate;
use common\models\PaymentTransaction;
use common\models\CurrencySetting;
use common\models\Order;

class WalletController extends Controller
{
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::className(),
        ];
        return $behaviors;
    }

    public function actionPaygates()
    {
        return Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();
    }

    public function actionCalculate() 
    {
        $request = Yii::$app->request;

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
        $user = Yii::$app->user->getIdentity();
        $form = new \payment\forms\WalletPaymentForm([
            'quantity' => $request->post('quantity', 0),
            'paygate' => $request->post('paygate'),
            'remark' => $request->post('name'),
            'token' => $request->get('token')
        ]);

        if ($form->validate() && $trnId = $form->purchase()) {
            $paygate = $form->getPaygate();
            $trn = PaymentTransaction::findOne($trnId);
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
                $transaction = $model->getTransaction();
                $user = Yii::$app->user->getIdentity();
                $tId = $transaction->getId();
                // Sending email
                $emailHelper = new \common\components\helpers\MailHelper();
                $emailHelper
                ->setMailer(Yii::$app->mailer)
                ->usingCustomerService()
                ->usingKinggemsSiteName()
                ->send(
                    sprintf("Deposit Notification [%s - %s]", $transaction->remark, $transaction->total_price),
                    $user->email,
                    'notify',
                    [
                        'transaction' => $transaction,
                        'user' => $user
                    ]
                );
                
                return $this->asJson(['status' => true, 'message' => sprintf("Thanks for shopping with us. Your payment is on processing. Kindly save your tracking number: %s", $tId), 'id' => $tId]);
            } else {
                $errors = $model->getErrorSummary(true);
                $error = reset($errors);
                return $this->asJson(['status' => false, 'errors' => $error]);
            }
        }
    }
}