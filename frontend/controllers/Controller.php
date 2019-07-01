<?php
namespace frontend\controllers;

use Yii;
use common\components\Controller as BaseController;
use frontend\forms\LoginForm;

/**
 * Controller
 */
class Controller extends BaseController
{
	public function beforeAction($action)
	{
		if (parent::beforeAction($action)) {
			// // Login form
			// $model = new LoginForm();
            // $this->view->params['top_login_form'] = $model;
            return true;
        }
        return false;
	}
}