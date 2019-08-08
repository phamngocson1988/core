<?php
namespace backend\controllers;

use Yii;
use common\components\Controller as BaseController;
use yii\web\Response;
use backend\forms\FetchNewPendingOrderForm;
use backend\models\UserAffiliate;
use backend\models\UserCommissionWithdraw;

/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			// Show number of new pending order
			$form = new FetchNewPendingOrderForm();
	        $command = $form->getCommand();
	        $total = $command->count();
            $this->view->params['new_pending_order'] = $total ? $total : '';

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