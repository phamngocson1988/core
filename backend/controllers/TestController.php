<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchImageForm;
use yii\helpers\Url;
use backend\forms\UploadImageForm;
use backend\forms\DeleteImageForm;
use common\forms\SendmailForm;

/**
 * TestController
 */
class TestController extends Controller
{

	public function actions()
	{
		return [
			'autocomplete' => [
				'class' => 'backend\controllers\actions\AutocompleteAction',
				'tableName' => \common\models\Game::tableName(),
				'field' => 'title'
			]
		];
	}

	public function actionIndex()
	{
		// $file = \common\models\Image::findOne(15);
		// echo Yii::$app->image->get($file);die;
		$model = new \common\models\Game();
		return $this->render('index', ['model' => $model]);
	}

	public function actionSearch()
	{
		// $file = \common\models\Image::findOne(15);
		// echo Yii::$app->image->get($file);die;
		$model = new \common\models\Game();
		return $this->render('search', ['model' => $model]);
	}


	public function actionAjaxUpload()
	{
        $data = Yii::$app->image->upload('imageFiles');
        return $this->redirect('index');
	}

	public function actionTree()
	{
		$this->view->registerCssFile('vendor/assets/global/plugins/jstree/dist/themes/default/style.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
        $this->view->registerJsFile('vendor/assets/global/plugins/jstree/dist/jstree.min.js', ['depends' => '\backend\assets\AppAsset']);
        $this->view->registerJsFile('vendor/assets/pages/scripts/ui-tree.min.js', ['depends' => '\backend\assets\AppAsset']);
		return $this->render('tree.tpl');
	}

	public function actionMultiple()
	{
		$model = new \common\models\Game();
		return $this->render('multiple', ['model' => $model]);
	}

	public function actionEmail()
	{
		$request = Yii::$app->request;
		$form = new SendmailForm();
		if ($request->isPost) {
			$mailer = Yii::createObject([
				'class' => '\yii\swiftmailer\Mailer',
				'viewPath' => '@common/mail',
				'transport' => [
					'class' => 'Swift_SmtpTransport',
					'host' => 'smtp.gmail.com',
					'username' => 'customerservice.kinggems@gmail.com', //'info.globalprepaidcard@gmail.com',
					'password' => 'K12345678$', //'huynhgia072017',
					'port' => '587',
					'encryption' => 'tls',
				],            
				'useFileTransport' => false,
			]);
			$form->setMailer($mailer);
			$form->subject = 'Test cation';
			$form->body = 'Test body';
			$form->send($request->post('email'));
            Yii::$app->session->setFlash('success', 'Success!');
		}
		return $this->render('email', ['model' => $form]);
	}
}