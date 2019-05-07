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
    public $folder;

    public function init()
    {
        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
    }

    public function saveFile($file, $fileModel)
    {
        $option = [
            "public_id" => $this->getPublicId($fileModel)
        ];

        return \Cloudinary\Uploader::upload($file->tempName, $option);
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
        return implode("/", array_filter([$this->folder, $fileModel->id]));
    }
}