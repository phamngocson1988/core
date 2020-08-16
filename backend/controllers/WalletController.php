<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\forms\FetchWalletForm;
use yii\data\Pagination;
use backend\models\User;
use backend\models\UserWallet;

/**
 * WalletController
 */
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
        $data = [
            'created_at_from' => $request->get('created_at_from'),
            'created_at_to' => $request->get('created_at_to'),
        //     'id' => $request->get('id'),
            'id' => $request->get('id'),
            'user_id' => $request->get('user_id'),
        //     'payment_type' => $request->get('payment_type'),
        //     'status' => $request->get('status'),
        ];
        $search = new FetchWalletForm($data);
        $command = $search->getCommand();
        $pages = new Pagination(['totalCount' => $command->count()]);
        $models = $command->offset($pages->offset)
                            ->limit($pages->limit)
                            ->orderBy(['id' => SORT_DESC])
                            ->all();
        return $this->render('index', [
            'search' => $search,
            'models' => $models,
            'pages' => $pages
        ]);
	}

	public function actionTopup($id)
	{
		$this->view->params['main_menu_active'] = 'wallet.index';
		$user = User::findOne($id);
		if (!$user) throw new NotFoundHttpException('Not found');
        $balance = $user->getWalletAmount();
		$request = Yii::$app->request;
		if ($request->isPost) {
			$coin = ArrayHelper::getValue($request->post(), 'coin');
			$description = ArrayHelper::getValue($request->post(), 'description');
			if ($coin) {
				$admin = Yii::$app->user->identity;
				$user->topup($coin, null, sprintf("%s (ID: %s) have done this topup with description: %s", $admin->username, $admin->id, $description));
				return $this->redirect(['wallet/index', 'user_id' => $id]);	
			}
			Yii::$app->session->setFlash('error', 'Số coin nạp vào không được để trống');
		}
		return $this->render('topup', [
			'user' => $user,
            'balance' => $balance
		]);
	}

    public function actionWithdraw($id)
    {
        $this->view->params['main_menu_active'] = 'wallet.index';
        $user = User::findOne($id);
        if (!$user) throw new NotFoundHttpException('Not found');
        $balance = $user->getWalletAmount();
        $request = Yii::$app->request;
        if ($request->isPost) {
            $coin = ArrayHelper::getValue($request->post(), 'coin');
            $description = ArrayHelper::getValue($request->post(), 'description');
            if ($coin && $coin <= $balance) {
                $admin = Yii::$app->user->identity;
                $user->withdraw($coin, null, sprintf("%s (ID: %s) have done this withdraw with description: %s", $admin->username, $admin->id, $description));
                return $this->redirect(['wallet/index', 'user_id' => $id]);   
            }
            Yii::$app->session->setFlash('error', 'Số coin rút ra không hợp lệ');
        }
        return $this->render('withdraw', [
            'user' => $user,
            'balance' => $balance
        ]);
    }
}