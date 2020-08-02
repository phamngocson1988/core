<?php
namespace common\components\filesystem\cloudinary;

use Yii;
use yii\base\Model;
use common\components\filesystem\FileSystemInterface;

/**
 * Adapter to integrate Cloudinary functions to Yii
 *
 * @link https://cloudinary.com/documentation/php_integration
 */
class CloudinaryFileSystem extends Model implements FileSystemInterface
{
    public $cloud_name;
    public $api_key;
    public $api_secret;
    public $file_path;

    public function init()
    {
        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
    }

    public function save($file, $fileModel)
    {
        $option = [
            "public_id" => $this->getPublicId($fileModel),
            "filename" => $fileModel->getName()
        ];

        return \Cloudinary\Uploader::upload($file->tempName, $option);
    }

    public function upload($file, $path, $includeSchema = false)
    {
        $option = [
            "public_id" => $path,
            "filename" => $file->name
        ];
        return \Cloudinary\Uploader::upload($file->tempName);
    }

    public function getUrl($fileModel)
    {
        $options = [];
        $id = $this->getPublicId($fileModel);
        return cloudinary_url($id, $options);
    }

    public function getPath($fileModel)
    {
        return $this->getPublicId($fileModel);
    }

    protected function getPublicId($fileModel)
    {
        if (!is_object($fileModel)) {
            return sprintf("%s/%s",  Yii::getAlias($this->file_path), $fileModel);
        } else {
            $fileDir = sprintf("%s/%s", Yii::getAlias($this->file_path), $this->getRelativePath($fileModel->id));
            $filePath = sprintf("%s/%s", $fileDir, $fileModel->getName());
            return $filePath;
        }

        // return implode("/", array_filter([$this->file_path, $fileModel->id]));
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
        return $this->getUrl($fileModel);
    }
}