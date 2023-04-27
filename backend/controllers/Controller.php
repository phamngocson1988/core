<?php
namespace backend\controllers;

use Yii;
use common\components\Controller as BaseController;
use yii\web\Response;
use backend\models\Affiliate;
use backend\models\AffiliateCommissionWithdraw;
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
			// Show number of new verifying orders
			$verifyingCommand = Order::find()->where(['status' => Order::STATUS_VERIFYING]);
	        $verifyingTotal = $verifyingCommand->count();
            $this->view->params['new_verifying_order'] = $verifyingTotal ? $verifyingTotal : '';

			// Show number of new pending orders
	        $pendingForm = new \backend\forms\FetchPendingShopForm();
	        $newPendingOrderTotal = $pendingForm->count();
			$this->view->params['new_pending_order'] = $newPendingOrderTotal ? $newPendingOrderTotal : '';

			// Show number of new pending information orders
	        $pendingInformationForm = new \backend\forms\FetchPendingInformationShopForm();
	        $newPendingInfoOrderTotal = $pendingInformationForm->count();
			$this->view->params['new_pending_info_order'] = $newPendingInfoOrderTotal ? $newPendingInfoOrderTotal : '';

			// Show number of new processing orders
            $processingForm = new \backend\forms\FetchProcessingShopForm();
	        $processingOrderTotal = $processingForm->count();
			$this->view->params['processing_order'] = $processingOrderTotal ? $processingOrderTotal : '';

			// Show number of new partial orders
            $partialForm = new \backend\forms\FetchPartialShopForm();
	        $partialOrderTotal = $partialForm->count();
			$this->view->params['partial_order'] = $partialOrderTotal ? $partialOrderTotal : '';

            // Show number of new cancelling orders
			$cancellingCommand = new \backend\forms\FetchCancelShopForm();
	        $cancellingTotal = $cancellingCommand->countCancelling();
            $this->view->params['cancelling_order'] = $cancellingTotal ? $cancellingTotal : '';

            // Show number of new affiliate request
	        $command = Affiliate::find()->where(['status' => Affiliate::STATUS_DISABLE]);
	        $affiliateTotal = $command->count();
            $this->view->params['new_affiliate_request'] = $affiliateTotal ? $affiliateTotal : '';

            // Show number of new commission withdraw
            $command = AffiliateCommissionWithdraw::find()->where(['status' => AffiliateCommissionWithdraw::STATUS_REQUEST]);
	        $commissionTotal = $command->count();
            $this->view->params['new_commission_withdraw'] = $commissionTotal ? $commissionTotal : '';

			$paymentCommentmentCount = \common\models\PaymentCommitment::find()->where(['status' => \common\models\PaymentCommitment::STATUS_PENDING])->count();
            $this->view->params['payment_commitment'] = $paymentCommentmentCount;

			$payemntRealityCount = \common\models\PaymentReality::find()->where(['status' => \common\models\PaymentReality::STATUS_PENDING])->count();
            $this->view->params['payment_reality'] = $payemntRealityCount;

			$withdrawRequestForm = new \backend\forms\FetchSupplierWithdrawRequestForm(['status' => [
				\common\models\SupplierWithdrawRequest::STATUS_REQUEST, \common\models\SupplierWithdrawRequest::STATUS_APPROVE
			]]);
			$this->view->params['withdraw_request'] = $withdrawRequestForm->getCommand()->count();
            return true;
        }

        return false;
	}
}