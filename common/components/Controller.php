<?php
namespace common\components;

use Yii;
use yii\web\Controller as BaseController;
use yii\web\Response;
use backend\forms\FetchNewPendingOrderForm;

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
            return true;
        }

        return false;
	}

	public function renderJson($status, $data = null, $errors = null)
	{
		$response = [
			'status' => $status,
			'data' => $data,
			'errors' => $errors
		];
		Yii::$app->response->format = Response::FORMAT_JSON;
		return $response;
	}

	public function redirectNotFound()
	{
		return $this->redirect(['site/index'], '404');
	}
}