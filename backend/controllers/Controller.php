<?php
namespace backend\controllers;

use Yii;
use common\components\Controller as BaseController;
use yii\web\Response;
use backend\models\Affiliate;
use backend\models\UserCommissionWithdraw;
use backend\models\Order;
use backend\forms\FetchTransactionForm;
/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			// Show number of new pending orders
			$newPendingOrderCommand = Order::find()->where(['status' => Order::STATUS_PENDING]);
	        $newPendingOrderTotal = $newPendingOrderCommand->count();
			$this->view->params['new_pending_order'] = $newPendingOrderTotal ? $newPendingOrderTotal : '';

			$newPendingInforOrderCommand = Order::find()->where(["IN", "status", [Order::STATUS_PENDING, Order::STATUS_PROCESSING]])
			->andWhere(["IS NOT", 'state', null]);
	        $newPendingInfoOrderTotal = $newPendingInforOrderCommand->count();
			$this->view->params['new_pending_info_order'] = $newPendingInfoOrderTotal ? $newPendingInfoOrderTotal : '';
			
			// Show number of new verifying orders
			$verifyingCommand = Order::find()->where(['status' => Order::STATUS_VERIFYING]);
	        $verifyingTotal = $verifyingCommand->count();
            $this->view->params['new_verifying_order'] = $verifyingTotal ? $verifyingTotal : '';

            // Show number of new cancelling orders
			$cancellingCommand = Order::find()->where(['IN', 'status', [Order::STATUS_VERIFYING, Order::STATUS_PENDING, Order::STATUS_PROCESSING]]);
        	$cancellingCommand->andWhere(['request_cancel' => 1]);
	        $cancellingTotal = $cancellingCommand->count();
            $this->view->params['cancelling_order'] = $cancellingTotal ? $cancellingTotal : '';

            // Show number of new processing orders
			$processingCommand = Order::find()->where(['status' => Order::STATUS_PROCESSING]);
	        $processingTotal = $processingCommand->count();
            $this->view->params['processing_order'] = $processingTotal ? $processingTotal : '';

            // Show number of new processing orders
			$partialCommand = Order::find()->where(['status' => Order::STATUS_PARTIAL]);
	        $partialTotal = $partialCommand->count();
            $this->view->params['partial_order'] = $partialTotal ? $partialTotal : '';

            // Show number of new affiliate request
	        $command = Affiliate::find()->where(['status' => Affiliate::STATUS_DISABLE]);
	        $affiliateTotal = $command->count();
            $this->view->params['new_affiliate_request'] = $affiliateTotal ? $affiliateTotal : '';

            // Show number of new commission withdraw
            $command = UserCommissionWithdraw::find()->where(['status' => UserCommissionWithdraw::STATUS_REQUEST]);
	        $commissionTotal = $command->count();
            $this->view->params['new_commission_withdraw'] = $commissionTotal ? $commissionTotal : '';

            // Show number of offline transaction
	        $offlineTransactionForm = new FetchTransactionForm([
	            'payment_type' => 'offline',
	            'status' => 'pending',
	        ]);
	        $offlineTransactionCommand = $offlineTransactionForm->getCommand();
	        $offlineTransactionCount = $offlineTransactionCommand->count();
            $this->view->params['new_offline_transaction'] = $offlineTransactionCount ? $offlineTransactionCount : '';
            return true;
        }

        return false;
	}
}