<?php
namespace common\components\filesystem\local;

use Yii;
use common\components\filesystem\FileSystemService;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

class LocalFileSystem extends FileSystemService
{
    public $image_path = '@common/uploads/images';
    public $image_url = 'http://image.core.com';

    public function saveToDisk($file, $fileModel)
    {
        $filePath = $this->getFilePath($fileModel);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir);
        $file->saveAs($filePath);
        return $filePath;
    }

    public function saveThumbnail($fileModel, $thumbnail) 
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

    public function get($fileModel, $thumbnail = null)
    {
        $fileDir = sprintf("%s/%s", $this->image_url, $this->getRelativePath($fileModel->id));
        if ($thumbnail) {
            $fileDir = sprintf("%s/%s", $fileDir, $thumbnail);
        }
        $fileUrl = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
        return $fileUrl;
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

    protected function getRelativePath($id)
    {
        $directory = [];
        $separator = DIRECTORY_SEPARATOR;
        while ($id) {
            $directory[] = substr($id, -3);
            if (strlen($id) - 3 < 0) {
                $id = 0;
            } else {
                $id = substr($id, 0, strlen($id) - 3);
            }
        }
        return implode($separator, $directory);
    }
}