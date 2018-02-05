<?php
namespace backend\forms;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\models\Image as TImage;
use yii\helpers\ArrayHelper;
use common\components\helpers\FileHelper;
use Yii;


class UploadImageForm extends Model
{
    /**
     * @var UploadedFile[]
     */
    public $imageFiles;

    private $_images = [];

    public function rules()
    {
        return [
            [['imageFiles'], 'file', 'skipOnEmpty' => false, 'extensions' => 'png, jpg', 'maxFiles' => 4],
        ];
    }

    public static function getThumbnails()
    {
        return (array)Yii::$app->params['thumbnails'];
    }

    public function upload()
    {
        if (!$this->validate()) { 
            return false;
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $thumbnails = self::getThumbnails();
            foreach ($this->imageFiles as $file) {
                // Save database.
                $fileModel = new TImage();
                $fileModel->name = $file->baseName;
                $fileModel->extension = $file->extension;
                $fileModel->size = $file->size;
                $fileModel->created_at = strtotime('now');
                $fileModel->created_by = Yii::$app->user->id;
                $fileModel->save();

                // Save to disk
                $absolutePath = $fileModel->getAbsolutePath();
                FileHelper::createDirectory($absolutePath);
                $filePath = $fileModel->getPath();
                $file->saveAs($filePath);


                foreach ($thumbnails as $thumbSize) {
                    $fileModel->saveThumb($thumbSize);
                }
                $this->_images[] = $fileModel;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }

    public function getImages()
    {
        return (array)$this->_images;
    }
}