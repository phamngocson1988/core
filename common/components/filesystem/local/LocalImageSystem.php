<?php
namespace common\components\filesystem\local;

use Yii;
use yii\base\Model;
use common\components\filesystem\ImageSystemInterface;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;
use yii\imagine\Image;

class LocalImageSystem extends Model implements ImageSystemInterface
{
    public $image_path = '@common/uploads/images';
    public $image_url = 'http://image.core.com';
    public $auto_generate_thumb = false;

    public function saveImage($file, $fileModel)
    {
        $filePath = $this->getFilePath($fileModel);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir, 0771);
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
        FileHelper::createDirectory($thumbDir, 0771);
        $thumbWidth = ArrayHelper::getValue($sizes, 0);
        $thumbHeight = ArrayHelper::getValue($sizes, 1);
        $thumb = Image::thumbnail($filePath, $thumbWidth, $thumbHeight);
        $thumb->save($thumbPath);
        return $this->get($fileModel, $thumbnail);
    }    

    public function get($fileModel, $thumbnail = null)
    {
        $fileDir = sprintf("%s/%s", $this->image_url, $this->getRelativePath($fileModel->id));
        if ($thumbnail) {
            $fileDir = sprintf("%s/%s", $fileDir, $thumbnail);
        }
        $fileUrl = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());

        if ($this->auto_generate_thumb === true) { // check if the resource is not exist. system will generate it
            $file_headers = @get_headers($fileUrl);
            if (!$file_headers 
                || $file_headers[0] == 'HTTP/1.1 404 Not Found'
                || $file_headers[0] == 'HTTP/1.1 400 Bad Request'
            ) { // not exist
                $fileUrl = $this->saveThumbnail($fileModel, $thumbnail);
            }    
        }
        
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