<?php
namespace website\controllers;

use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\filters\AccessControl;
use yii\data\Pagination;

// models
use website\models\Paygate;

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

}