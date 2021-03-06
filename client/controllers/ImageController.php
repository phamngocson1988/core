<?php
namespace client\controllers;

use Yii;
use common\components\Controller;
use yii\filters\AccessControl;
use client\forms\FetchImageForm;
use yii\helpers\Url;
use yii\web\UploadedFile;
use client\forms\DeleteImageForm;

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
                $html .= $this->renderPartial("$template.tpl",['model' => $model]);
            }
            $data = [
                'items' => $html,
                'total' => $total,
                'load_more' => $total > ($offset + $limit)
            ];
            return $this->renderJson(true, $data);
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
            if ($request->post('review_width') && $request->post('review_height')) {
                $size = sprintf("%sx%s", $request->post('review_width'), $request->post('review_height'));

                $imageArray = [];
                foreach ($images as  $image) {
                    $info = [];
                    $info['id'] = $imageId = $image->getId();
                    $info['thumb'] = $image->getUrl($size);
                    $info['src'] = $image->getUrl();
                    foreach (Yii::$app->params['thumbnails'] as $thumbnail) {
                        $info['size'][$thumbnail] = $image->getUrl($thumbnail);
                    }
                    $imageArray[$imageId] = $info;
                }

                $data = $imageArray;
            }
        } else {
            $errors = Yii::$app->image->getErrors($attribute);
        }
        
        return $this->renderJson($result, $data, $errors);
    }

    public function actionAjaxDelete($id)
    {
        $request = Yii::$app->request;
        if (!$request->isAjax) {
            return;
        }
        $model = new DeleteImageForm(['id' => $id]);
        return $this->renderJson($model->delete(), [], $model->getErrors());
    }

    public function actionPopup()
    {
        $request = Yii::$app->request;
        $result = ['status' => false];
        $defaultThumbnail = '150x150';
        return $this->renderPartial('popup.tpl', ['default_thumbnail' => $defaultThumbnail]);
    }

    public function actionEditor()
    {
        try {
            // File Route.
            $fileRoute = Yii::$app->image->image_path;
            $fileUrl = Yii::$app->image->image_url;
            $fieldname = "file";
            // Get filename.
            $filename = explode(".", $_FILES[$fieldname]["name"]);

            // Validate uploaded files.
            // Do not use $_FILES["file"]["type"] as it can be easily forged.
            $finfo = finfo_open(FILEINFO_MIME_TYPE);

            // Get temp file name.
            $tmpName = $_FILES[$fieldname]["tmp_name"];

            // Get mime type.
            $mimeType = finfo_file($finfo, $tmpName);

            // Get extension. You must include fileinfo PHP extension.
            $extension = end($filename);

            // Allowed extensions.
            $allowedExts = Yii::$app->image->extensions;

            // Allowed mime types.
            $allowedMimeTypes = Yii::$app->image->mimeTypes;

            // Validate image.
            if (!in_array(strtolower($mimeType), $allowedMimeTypes) || !in_array(strtolower($extension), $allowedExts)) {
                throw new \Exception("File does not meet the validation.");
            }

            // Generate new random name.
            $name = sha1(microtime()) . "." . $extension;
            $fullNamePath = $fileRoute . "/" . $name;

            // Check server protocol and load resources accordingly.
            if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] != "off") {
                $protocol = "https://";
            } else {
                $protocol = "http://";
            }

            // Save file in the uploads folder.
            move_uploaded_file($tmpName, $fullNamePath);

            // Generate response.
            $response = new \StdClass;
            $response->location = $fileUrl . '/' . $name;

            // Send response.
            echo stripslashes(json_encode($response));
        } catch (Exception $e) {
            // Send error response.
            echo $e->getMessage();
            http_response_code(404);
        }
    }
}
