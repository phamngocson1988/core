<?php
namespace client\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use client\forms\FetchImageForm;
use yii\helpers\Url;
use client\forms\UploadImageForm;
use client\forms\DeleteImageForm;

/**
 * TestController
 */
class TestController extends Controller
{

	public function actionIndex()
	{
		// $file = \common\models\Image::findOne(15);
		// echo Yii::$app->image->get($file);die;
		return $this->render('index.tpl');
	}

	public function actionAjaxUpload()
	{
        $data = Yii::$app->image->upload('imageFiles');
        return $this->redirect('index');
	}
}