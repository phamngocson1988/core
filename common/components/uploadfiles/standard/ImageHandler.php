<?php
namespace common\components\uploadfiles\standard;

use common\components\uploadfiles\UploadFiles;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;
use yii\web\UploadedFile;
use Yii;

class ImageHandler extends UploadFiles
{
    public $image_path = '@common/uploads/images';
    public $image_url = 'http://image.core.com';
    // public $file_path = 'C:/xampp/htdocs/core/common/uploads/files';
    // public $file_url = 'http://file.chuchu.com';

	/**
     * @param string $name file element name
     */
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
                foreach ($this->thumbnails as $thumbnail) {
                    $this->saveThumbnail($fileModel, $thumbnail);
                }
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

    public function get($fileModel, $thumbnail = null)
    {
        $fileDir = sprintf("%s/%s", $this->image_url, $this->getRelativePath($fileModel->id));
        if ($thumbnail) {
            $fileDir = sprintf("%s/%s", $fileDir, $thumbnail);
        }
        $fileUrl = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
        return $fileUrl;
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
        $file->saveAs($filePath);
        return $filePath;
    }
    
    protected function saveThumbnail($fileModel, $thumbnail) 
    {
        $sizes = explode("x", $thumbnail);
        if (count($sizes) < 2) {
            return;
        }
     
        $filePath = $this->getFilePath($fileModel);        
        $thumbPath = $this->getFilePath($fileModel, $thumbnail);
        $thumbDir = dirname($thumbPath);
        FileHelper::createDirectory($thumbDir);
        $thumbWidth = ArrayHelper::getValue($sizes, 0);
        $thumbHeight = ArrayHelper::getValue($sizes, 1);
        $thumb = Image::thumbnail($filePath, $thumbWidth, $thumbHeight);
        $thumb->save($thumbPath);
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