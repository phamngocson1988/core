<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchImageForm;
use yii\helpers\Url;
use backend\forms\UploadImageForm;
use backend\forms\DeleteImageForm;

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
}