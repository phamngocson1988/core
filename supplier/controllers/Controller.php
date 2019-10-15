<?php
namespace supplier\controllers;

use Yii;
use common\components\Controller as BaseController;

/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			// Show number of new pending orders
			// $newPendingOrderCommand = Order::find()->where(['status' => Order::STATUS_PENDING]);
	  //       $newPendingOrderTotal = $newPendingOrderCommand->count();
			// $this->view->params['new_pending_order'] = $newPendingOrderTotal ? $newPendingOrderTotal : '';
			return true;
        }

        return false;
	}
}