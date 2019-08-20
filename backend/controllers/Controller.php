<?php
namespace backend\controllers;

use Yii;
use common\components\Controller as BaseController;
use yii\web\Response;
use backend\models\UserAffiliate;
use backend\models\UserCommissionWithdraw;
use backend\models\Order;

/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			// Show number of new pending orders
			$newPendingOrderCommand = Order::find()->where(['status' => Order::STATUS_PENDING, 'orderteam_id' => null]);
	        $newPendingOrderTotal = $newPendingOrderCommand->count();
			$this->view->params['new_pending_order'] = $newPendingOrderTotal ? $newPendingOrderTotal : '';
			
			// Show number of new verifying orders
			$verifyingCommand = Order::find()->where(['status' => Order::STATUS_VERIFYING]);
	        $verifyingTotal = $verifyingCommand->count();
            $this->view->params['new_verifying_order'] = $verifyingTotal ? $verifyingTotal : '';

            // Show number of new cancelling orders
			$cancellingCommand = Order::find()->where(['<>', 'status', Order::STATUS_DELETED]);
        	$cancellingCommand->andWhere(['request_cancel' => 1]);
	        $cancellingTotal = $cancellingCommand->count();
            $this->view->params['cancelling_order'] = $cancellingTotal ? $cancellingTotal : '';

            // Show number of new processing orders
			$processingCommand = Order::find()->where(['status' => Order::STATUS_PROCESSING]);
	        $processingTotal = $processingCommand->count();
            $this->view->params['processing_order'] = $processingTotal ? $processingTotal : '';

            // Show number of new affiliate request
	        $command = UserAffiliate::find()->where(['status' => UserAffiliate::STATUS_DISABLE]);
	        $affiliateTotal = $command->count();
            $this->view->params['new_affiliate_request'] = $affiliateTotal ? $affiliateTotal : '';

            // Show number of new commission withdraw
            $command = UserCommissionWithdraw::find()->where(['status' => UserCommissionWithdraw::STATUS_REQUEST]);
	        $commissionTotal = $command->count();
            $this->view->params['new_commission_withdraw'] = $commissionTotal ? $commissionTotal : '';
            return true;
        }

        return false;
	}
}