<?php
namespace backend\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use backend\forms\FetchImageForm;
use yii\helpers\Url;
use backend\forms\UploadImageForm;
use yii\web\UploadedFile;
use backend\forms\DeleteImageForm;

/**
 * TestController
 */
class TestController extends Controller
{

	public function actionIndex()
	{
		return $this->render('index.tpl');
	}

	public function actionAjaxUpload()
	{
        $model = new \common\components\uploadfiles\standard\Standard();
        $files = UploadedFile::getInstancesByName('imageFiles');
        $data = $model->uploadFileFromForm($files);
        return $this->redirect('index');
	}
}