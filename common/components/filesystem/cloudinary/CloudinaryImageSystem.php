<?php
namespace common\components\filesystem\cloudinary;

use Yii;
use yii\base\Model;
use common\components\filesystem\ImageSystemInterface;

/**
 * Adapter to integrate Cloudinary functions to Yii
 *
 * @link https://cloudinary.com/documentation/php_integration
 */
class CloudinaryImageSystem extends Model implements ImageSystemInterface
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

    public function saveImage($file, $fileModel)
    {
        $option = [
            "public_id" => $this->getPublicId($fileModel)
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
        $id = $this->getPublicId($fileModel);
        return cloudinary_url($id, $options);
    }

    protected function getPublicId($fileModel)
    {
        return implode("/", array_filter([$this->folder, $fileModel->id]));
    }
}