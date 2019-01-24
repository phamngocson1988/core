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

    public function init()
    {
        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
    }

    public function saveImage($file, $fileModel)
    {
        $option = [
            "public_id" => $fileModel->id
        ];

        return \Cloudinary\Uploader::upload($file->tempName, $option);
    }

    public function saveThumbnail($fileModel, $thumbnail) 
    {
        return;
    }    

    public function get($fileModel, $thumbnail = null)
    {
        $options = [];
        if ($thumbnail) {
            list ($width, $height) = explode("x", $thumbnail);
            $options = ["width" => $width, "height" => $height, "crop" => "fill"];
        }

        return cloudinary_url($fileModel->id, $options);
    }
}