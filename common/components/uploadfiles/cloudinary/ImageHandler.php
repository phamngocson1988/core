<?php
namespace common\components\uploadfiles\cloudinary;

use Yii;
use common\components\uploadfiles\UploadFiles;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;

/**
 * Adapter to integrate Cloudinary functions to Yii
 *
 * @link https://cloudinary.com/documentation/php_integration
 */
class ImageHandler extends UploadFiles
{
    public $image_path = '@common/uploads/images';
    public $image_url = 'http://image.core.com';
    public $cloud_name;
    public $api_key;
    public $api_secret;

    public function init()
    {
        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
    }

    public function upload($name)
    {
        $files = [];
        try {
            $uploadedFiles = UploadedFile::getInstancesByName($name);
            $this->defineAttribute($name, $uploadedFiles);
            if (!$this->validate()) { 
                return false;
            }
            $transaction = Yii::$app->db->beginTransaction();
            foreach ($uploadedFiles as $file) {
                $fileModel = $this->saveToDatabase($file);
                $this->saveToDisk($file, $fileModel);
                // foreach ($this->thumbnails as $thumbnail) {
                //     $this->saveThumbnail($fileModel, $thumbnail);
                // }
                $files[] = $fileModel;
            }
            $transaction->commit();
            $this->undefineAttribute($name);
            return $files;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }



        
    }

    public function get($source, $options = array())
    {
        return cloudinary_url($source, ['secure' => true]);
    }

    public function getImagePath()
    {
        return '';
    }

    protected function saveToDatabase($file)
    {
        $fileModel = $this->instanceImageClass();
        $fileModel->name = $file->baseName;
        $fileModel->extension = $file->extension;
        $fileModel->size = $file->size;
        $fileModel->created_at = strtotime('now');
        $fileModel->created_by = Yii::$app->user->id;
        $fileModel->save();
        return $fileModel;
    }

    protected function saveToDisk($file, $fileModel)
    {
        $filePath = $this->getFilePath($fileModel);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir);
        // $file->saveAs($filePath);
        // return $filePath;




        // Set the id to option parameter
        $option = [
            "folder" => $filePath, 
            "public_id" => $fileModel->id
        ];

        // Up the file to Cloundinary from tmp
        return \Cloudinary\Uploader::upload($file->tempName, $option);
    }

    protected function getFilePath($fileModel, $thumbnail = null)
    {
        $fileDir = sprintf("%s/%s", Yii::getAlias($this->image_path), $this->getRelativePath($fileModel->id));
        if ($thumbnail) {
            $fileDir = sprintf("%s/%s", $fileDir, $thumbnail);
        }
        $filePath = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
        return $filePath;
    }


}