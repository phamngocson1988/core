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
			$status = [
				OrderSupplier::STATUS_REQUEST, 
				OrderSupplier::STATUS_APPROVE, 
				OrderSupplier::STATUS_PROCESSING, 
				OrderSupplier::STATUS_COMPLETED, 
			];
			$groupStatus = OrderSupplier::find()->where(['supplier_id' => $userId])
			->andWhere(['in', 'status', $status])
			->groupBy(['status'])
			->select(['status', 'COUNT(*) as count'])
			->asArray()
			->all();

			$statusCount = ArrayHelper::map($groupStatus, 'status', 'count');
			$requesting = ArrayHelper::getValue($statusCount, OrderSupplier::STATUS_REQUEST, 0);
			$pending = ArrayHelper::getValue($statusCount, OrderSupplier::STATUS_APPROVE, 0);
			$processing = ArrayHelper::getValue($statusCount, OrderSupplier::STATUS_PROCESSING, 0);
			$completed = ArrayHelper::getValue($statusCount, OrderSupplier::STATUS_COMPLETED, 0);

			$this->view->params['new_request_order'] = $requesting ? $requesting : '';
			$this->view->params['new_pending_order'] = $pending ? $pending : '';
			$this->view->params['new_processing_order'] = $processing ? $processing : '';
			$this->view->params['new_completed_order'] = $completed ? $completed : '';
			$this->view->params['new_confirmed_order'] = '';//$confirmed ? $confirmed : '';
			return true;
        }

        return false;
	}
}