<?php
namespace frontend\components\payment\actions;

use Yii;
use yii\web\BadRequestHttpException;
use yii\base\Action;
use frontend\models\UserWallet;
use frontend\models\Order;

class KinggemsPaygateAction extends Action
{
	public function run()
	{
		$request = Yii::$app->request;
		$user = Yii::$app->user->identity;
		$scenario = $request->get('scenario');
		switch ($scenario) {
			case 'create':
				return $this->createTransaction();
			case 'approve'
				$transactionId = $request->get('id');
				$success_url = $request->get('success_url');
				$error_url = $request->get('error_url');
				$verify_url = $request->get('verify_url');
				if ($this->approveTransaction($transactionId)) {
					$ch = curl_init(); 
		            curl_setopt($ch, CURLOPT_URL, $verify_url); 
		            curl_setopt($ch, CURLOPT_RETURNTRANSFER, FALSE); 
		            curl_exec($ch); 
            		curl_close($ch);
					return $this->redirect($success_url);
				} else {
					return $this->redirect($error_url);
				}
				break;
			default:
				return $this->asJson(['status' => false, 'message' => 'Invalid scenario']);
		}
	}

	protected function createTransaction()
	{
		$request = Yii::$app->request;
		$user = Yii::$app->user->identity;
		if ($request->post('secret') != Yii::$app->session->getFlash('kinggems_paygate')) {
			return $this->asJson(['status' => false, 'message' => 'Secrect code is invalid']);
		}
		if ($request->post('payer') != $user->getAuthKey()) {
			return $this->asJson(['status' => false, 'message' => 'Payer is invalid']);
		}
		$refId = $request->post('ref_key');
		$items = (array)$request->post('items', []);
		$totals = array_map(function($item) {
			return $item['quantity'] * $item['price'];
		}, $items);
		$totalPrice = array_sum($totals);
		try {
			$wallet = new UserWallet();
	        $wallet->coin = (-1) * $totalPrice;
	        $wallet->balance = $user->getWalletAmount() + $wallet->coin;
	        $wallet->type = UserWallet::TYPE_OUTPUT;
	        $wallet->description = "payment_id : $refId";
	        $wallet->ref_name = Order::classname();
	        $wallet->ref_key = $refId;
	        $wallet->created_by = $user->id;
	        $wallet->user_id = $user->id;
	        $wallet->status = UserWallet::STATUS_PENDING;
	        $wallet->payment_at = date('Y-m-d H:i:s');
	        if ($wallet->save()) {
	        	return $this->asJson(['status' => true, 'id' => $wallet->id]);
	        }
		} catch (\Exception $e) {
			return $this->asJson(['status' => false, 'message' => $e->getMessage()]);
		}
	}

	public function approveTransaction($id)
	{
		$wallet = UserWallet::findOne($id);
		if (!$wallet) return false;
		if ($wallet->status != UserWallet::STATUS_PENDING) return false;
		$wallet->status = UserWallet::STATUS_COMPLETED;
		$wallet->payment_at = date('Y-m-d H:i:s');
		return $wallet->save();
	}
}