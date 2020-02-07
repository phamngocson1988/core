<?php
namespace supplier\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\UploadedFile;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

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
        $resize = $request->post('resize', false);
        $files = Yii::$app->file->save($attribute);

        $result = false;
        $data = [];
        $errors = [];
        if ($files !== false) {
            $result = true;
            $fileArray = [];
            foreach ($files as  $file) {
                $path = $file->getPath();
                if ($resize) {
                    try {
                        $sizes = explode("x", $resize);
                        $w = array_shift($sizes);
                        $h = array_shift($sizes);
                        $w = ($w == 'auto' || !$w) ? null : $w;
                        $h = ($h == 'auto' || !$h) ? null : $h;
                        Image::resize($path, $w, $h)->save($path);
                    } catch (Exception $t) {
                        // Handle exception
                        $resize = false;
                    }
                }
                $info = [];
                $info['id'] = $fileId = $file->getId();
                $info['src'] = $file->getUrl();
                $info['path'] = $path;
                $fileArray[] = $info;
            }

            $data = $fileArray;
        } else {
            $errors = Yii::$app->file->getErrors($attribute);
        }
        
        return $this->renderJson($result, $data, $errors);
    }
}
