<?php
namespace common\components\filesystem\local;

use Yii;
use yii\base\Model;
use common\components\filesystem\FileSystemInterface;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

class LocalFileSystem extends Model implements FileSystemInterface
{
    public $file_path = '@common/uploads/files';
    public $file_url = 'http://file.core.com';

    public function save($file, $fileModel)
    {
        $filePath = $this->getPath($fileModel);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir);
        $file->saveAs($filePath);
        return $filePath;
    }

    public function upload($file, $path)
    {
        $location = sprintf("%s/%s", $path, date('YmdHis') . $file->name);
        $filePath = sprintf("%s/%s", Yii::getAlias($this->file_path), $location);
        $fileDir = dirname($filePath);
        FileHelper::createDirectory($fileDir);
        $file->saveAs($filePath);
        return sprintf("%s/%s", $this->file_url, $location);
    }

    public function getUrl($fileModel)
    {
        $fileDir = sprintf("%s/%s", $this->file_url, $this->getRelativePath($fileModel->id));
        $fileUrl = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
        return $fileUrl;
    }
    
   

    public function getPath($fileModel)
    {
        $fileDir = sprintf("%s/%s", Yii::getAlias($this->file_path), $this->getRelativePath($fileModel->id));
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