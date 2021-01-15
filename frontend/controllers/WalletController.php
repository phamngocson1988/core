<?php
namespace frontend\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;
use common\components\helpers\StringHelper;

// models
use frontend\models\Paygate;
use frontend\models\PaymentTransaction;
use common\models\Currency;

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
        $request = Yii::$app->request;
        $paygates = Paygate::find()->where(['status' => Paygate::STATUS_ACTIVE])->all();

        // pending transaction
        $pendings = PaymentTransaction::find()->where([
            'status' => PaymentTransaction::STATUS_PENDING,
            'user_id' => Yii::$app->user->id
        ])->all();

        // history transaction
        $form = new \frontend\forms\FetchPaymentTransactionForm([
            'user_id' => Yii::$app->user->id,
            'status' => $request->get('status'),
            'start_date' => $request->get('start_date'),
            'end_date' => $request->get('end_date'),
        ]);
        $command = $form->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $transactions = $command->offset($pages->offset)->limit($pages->limit)->all();

    	return $this->render('index', [
            'paygates' => $paygates,
            'pendings' => $pendings,
            'transactions' => $transactions,
            'pages' => $pages,
            'search' => $form,
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
        $model = new \frontend\forms\UpdateTransactionForm(['id' => $id]);
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

        $form = new \frontend\forms\WalletPaymentForm([
            'quantity' => $request->post('quantity', 0),
            'voucher' => $request->post('voucher', ''),
            'paygate' => $request->post('paygate'),
        ]);

        if ($form->validate()) {
            $calculate = $form->calculate();
            $totalPayment = StringHelper::numberFormat($calculate['totalPayment'], 2);
            $paygate = $form->getPaygate();
            if ($paygate->currency != 'USD') {
                $otherCurrencyTotal = Currency::convertUSDToCurrency(StringHelper::numberFormat($totalPayment, 2), $paygate->currency);
                $currencyModel = Currency::findOne($paygate->currency);
                $otherCurrency = $currencyModel->addSymbolFormat(StringHelper::numberFormat($otherCurrencyTotal, 2));
                // $totalPayment = sprintf("%s (%s)", $totalPayment, utf8_encode($otherCurrency));
                $totalPayment = sprintf("%s (%s)", $totalPayment, $otherCurrency);
            }
            $data = [
                'subTotalKingcoin' => StringHelper::numberFormat($calculate['subTotalKingcoin'], 2),
                'bonusKingcoin' => StringHelper::numberFormat($calculate['bonusKingcoin'], 2),
                'totalKingcoin' => StringHelper::numberFormat($calculate['totalKingcoin'], 2),
                'subTotalPayment' => StringHelper::numberFormat($calculate['subTotalPayment'], 2),
                'voucherApply' => StringHelper::numberFormat($calculate['voucherApply'], 2),
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
        if (Yii::$app->user->isGuest) return json_encode(['status' => false, 'errors' => 'You need to login']);

        $form = new \frontend\forms\WalletPaymentForm([
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
        $model = new \frontend\forms\UpdateTransactionForm(['id' => $id]);
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