<?php
namespace common\components;

use Yii;
use yii\web\Controller as BaseController;
use yii\web\Response;

/**
 * Controller
 */
class Controller extends BaseController
{

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
}