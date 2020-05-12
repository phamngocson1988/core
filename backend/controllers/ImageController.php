<?php
namespace backend\controllers;

use Yii;
use yii\filters\AccessControl;
use backend\forms\FetchImageForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use backend\forms\DeleteImageForm;
use yii\helpers\ArrayHelper;

/**
 * ImageController
 */
class ImageController extends Controller
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

    public function actionAjaxLoad()
    {
        $request = Yii::$app->request;
        if ($request->isAjax) {
            $offset = $request->get('offset', 1);
            $limit = $request->get('limit', 8);
            $data = [
                'offset' => $offset,
                'limit' => $limit
            ];
            $form = new FetchImageForm($data);
            $models = $form->fetch();
            $total = $form->count();
            $html = "";
            $template = $request->get('template', '_item');
            foreach ($models as $model) {
                $html .= $this->renderPartial("$template.php",['model' => $model]);
            }
            $data = [
                'items' => $html,
                'total' => $total,
                'load_more' => $total > ($offset + $limit)
            ];
            return $this->asJson([
                'status' => true,
                'data' => $data,
            ]);
        }
    }    

    public function actionAjaxUpload()
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return;
        }
        $attribute = $request->post('name', 'imageFiles');
        $images = Yii::$app->image->upload($attribute);
        $result = false;
        $data = [];
        $errors = [];
        if ($images !== false) {
            $result = true;
            $size = null;
            if ($request->post('review_width') && $request->post('review_height')) {
                $size = sprintf("%sx%s", $request->post('review_width'), $request->post('review_height'));
            }
            $imageArray = [];
            foreach ($images as  $image) {
                $info = [];
                $info['id'] = $imageId = $image->getId();
                $info['thumb'] = $image->getUrl($size);
                $info['src'] = $image->getUrl();
                foreach (Yii::$app->image->thumbnails as $thumbnail) {
                    $info['size'][$thumbnail] = $image->getUrl($thumbnail);
                }
                $imageArray[$imageId] = $info;
            }

            $data = $imageArray;
        } else {
            $errors = Yii::$app->image->getErrors($attribute);
        }
        
        return $this->asJson([
            'status' => $result,
            'data' => $data,
        ]);
    }

    public function actionAjaxDelete($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return;
        }
        $model = new DeleteImageForm(['id' => $id]);
        return $this->asJson([
            'status' => $model->delete(),
            'data' => [],
            'errors' => $model->getErrors()
        ]);
    }

    public function actionPopup()
    {
        $request = Yii::$app->request;
        $result = ['status' => false];
        $defaultThumbnail = $request->get('thumbnail', '500x500');
        return $this->renderPartial('popup.php', ['default_thumbnail' => $defaultThumbnail]);
    }

    public function actionEditor()
    {
        try {
            $attribute = "file";
            $images = Yii::$app->image->upload($attribute);
            if ($images !== false) {
                $image = reset($images);
                // Generate response.
                $response = new \StdClass;
                $response->location = $image->getUrl();
                // Send response.
                echo stripslashes(json_encode($response));
            } else {
                throw new Exception("Error Processing Request", 1);
            }
        } catch (Exception $e) {
            // Send error response.
            echo $e->getMessage();
            http_response_code(404);
        }
    }
}
