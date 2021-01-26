<?php
namespace common\components\filesystem\local;

use Yii;
use yii\base\Model;
use common\components\filesystem\FileSystemInterface;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

class LocalFileSystem extends Model implements FileSystemInterface
{
    public $file_path = '@common/uploads/files';
    public $file_url = 'http://file.core.com';

    public function save($file, $fileModel)
    {
        $filePath = $this->getPath($fileModel);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir, 0771);
        $file->saveAs($filePath);
        return $filePath;
    }

    public function upload($file, $path, $includeSchema = false)
    {
        $location = sprintf("%s/%s", $path, date('YmdHis') . urlencode($file->name));
        $filePath = sprintf("%s/%s", Yii::getAlias($this->file_path), $location);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir, 0771);
        $file->saveAs($filePath);
        if (!$includeSchema) return $location;//sprintf("%s/%s", $this->file_url, $location);
        return sprintf("%s/%s", $this->file_url, $location);
    }

    public function getUrl($fileModel)
    {
        if (!is_object($fileModel)) {
            return sprintf("%s/%s", $this->file_url, $fileModel);
        } else {
            $fileDir = sprintf("%s/%s", $this->file_url, $this->getRelativePath($fileModel->id));
            $fileUrl = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
            return $fileUrl;
        }
    }
    
   

    public function getPath($fileModel)
    {
        $filePath = implode(DIRECTORY_SEPARATOR, explode('/', Yii::getAlias($this->file_path)));
        if (!is_object($fileModel)) {
            // return sprintf("%s/%s",  Yii::getAlias($this->file_path), $fileModel);
            return implode(DIRECTORY_SEPARATOR, [
                $filePath, 
                $fileModel
            ]);
        } else {
            // $fileDir = sprintf("%s/%s", Yii::getAlias($this->file_path), $this->getRelativePath($fileModel->id));
            // $filePath = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
            // return $filePath;
            return implode(DIRECTORY_SEPARATOR, [
                $filePath, 
                $this->getRelativePath($fileModel->id),
                sprintf("%s.%s", $fileModel->getName(), $fileModel->getExtension())
            ]);
        }
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

    public function getThumb($fileModel, $thumbnail = null) 
    {
        if (!$thumbnail) return $this->getUrl($fileModel);
        else {
            // create thumb
            $thumbPath = $this->saveThumbnail($fileModel, $thumbnail);
            if (!$thumbPath) return $this->getUrl($fileModel);
            // If thumbnail file is exist
            $fileDir = sprintf("%s/%s", $this->file_url, $this->getRelativePath($fileModel->id));
            $fileUrl = sprintf("%s/%s-%s.%s", $fileDir, $fileModel->getName(), $thumbnail, $fileModel->getExtension());
            return $fileUrl;
        }
    }

    public function saveThumbnail($fileModel, $thumbnail) 
    {
        $sizes = explode("x", $thumbnail);
        if (count($sizes) < 2) {
            return;
        }
        try {
            $filePath = $this->getPath($fileModel);        
            $fileDir = dirname($filePath);
            $thumbPath = sprintf("%s/%s-%s.%s", $fileDir, $fileModel->getName(), $thumbnail, $fileModel->getExtension());
            if (file_exists($thumbPath)) return $thumbPath;
            $thumbWidth = ArrayHelper::getValue($sizes, 0);
            $thumbHeight = ArrayHelper::getValue($sizes, 1);
            $thumb = Image::thumbnail($filePath, $thumbWidth, $thumbHeight);
            $thumb->save($thumbPath);
            return $thumbPath;
        } catch (\Exception $e) {
            return false;
        }
    }    
}