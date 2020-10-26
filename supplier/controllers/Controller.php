<?php
namespace supplier\controllers;

use Yii;
use common\components\Controller as BaseController;
use supplier\models\Order;
use supplier\models\OrderSupplier;
use yii\helpers\ArrayHelper;

/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action)
	{
		$userId = Yii::$app->user->id;
		if (parent::beforeAction($action)) {
			// Count order by status
			$this->view->params['new_completed_order'] = '';
			$this->view->params['new_confirmed_order'] = '';

			$waitingForm = new \supplier\forms\FetchWaitingShopForm(['supplier_id' => Yii::$app->user->id]);
	        $newWaitingOrderTotal = $waitingForm->count();
			$this->view->params['new_request_order'] = $newWaitingOrderTotal ? $newWaitingOrderTotal : '';

			$pendingForm = new \supplier\forms\FetchPendingShopForm(['supplier_id' => Yii::$app->user->id]);
	        $newPendingOrderTotal = $pendingForm->count();
			$this->view->params['new_pending_order'] = $newPendingOrderTotal ? $newPendingOrderTotal : '';

			$processingForm = new \supplier\forms\FetchProcessingShopForm(['supplier_id' => Yii::$app->user->id]);
	        $processingOrderTotal = $processingForm->count();
			$this->view->params['new_processing_order'] = $processingOrderTotal ? $processingOrderTotal : '';
			return true;
        }

        return false;
	}
}