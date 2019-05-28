<?php
namespace backend\controllers;

use Yii;
use backend\controllers\Controller;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;

/**
 * FileController
 */
class FileController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function actionAjaxUpload()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return;
        }
        $attribute = $request->post('name', 'upload_file');
        $files = Yii::$app->file->upload($attribute);

        $result = false;
        $data = [];
        $errors = [];
        if ($files !== false) {
            $result = true;
            $fileArray = [];
            foreach ($files as  $file) {
                $info = [];
                $info['id'] = $fileId = $file->getId();
                $info['src'] = $file->getUrl();
                $info['path'] = $file->getPath();
                $fileArray[] = $info;
            }

            $data = $fileArray;
        } else {
            $errors = Yii::$app->file->getErrors($attribute);
        }
        
        return $this->renderJson($result, $data, $errors);
    }
}
