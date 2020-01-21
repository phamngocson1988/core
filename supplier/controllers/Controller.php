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
			$status = [Order::STATUS_PENDING, Order::STATUS_PROCESSING, Order::STATUS_COMPLETED, Order::STATUS_CONFIRMED];
			$groupStatus = Order::find()->where(['supplier_id' => $userId])
			->andWhere(['in', 'status', $status])
			->groupBy(['status'])
			->select(['status', 'COUNT(*) as count'])
			->asArray()
			->all();
			$statusCount = ArrayHelper::map($groupStatus, 'status', 'count');
			$pending = ArrayHelper::getValue($statusCount, Order::STATUS_PENDING, 0);
			$processing = ArrayHelper::getValue($statusCount, Order::STATUS_PROCESSING, 0);
			$completed = ArrayHelper::getValue($statusCount, Order::STATUS_COMPLETED, 0);
			$confirmed = ArrayHelper::getValue($statusCount, Order::STATUS_CONFIRMED, 0);
			$this->view->params['new_pending_order'] = $pending ? $pending : '';
			$this->view->params['new_processing_order'] = $processing ? $processing : '';
			$this->view->params['new_completed_order'] = $completed ? $completed : '';
			$this->view->params['new_confirmed_order'] = $confirmed ? $confirmed : '';

			// Count new order
			$requesting = OrderSupplier::find()->where([
				'order_supplier.supplier_id' => $userId,
				'order_supplier.status' => OrderSupplier::STATUS_REQUEST
			])
			->joinWith('order')
			->andWhere(['in', 'order.status', [
				Order::STATUS_PENDING,
				Order::STATUS_PROCESSING,
				Order::STATUS_COMPLETED,
			]])
			->count();
			$this->view->params['new_request_order'] = $requesting ? $requesting : '';
			return true;
        }

        return false;
	}
}