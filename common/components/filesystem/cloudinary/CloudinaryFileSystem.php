<?php
namespace common\components\filesystem\cloudinary;

use Yii;
use common\components\filesystem\FileSystemService;
use yii\helpers\FileHelper;
use yii\helpers\ArrayHelper;

/**
 * Adapter to integrate Cloudinary functions to Yii
 *
 * @link https://cloudinary.com/documentation/php_integration
 */
class CloudinaryFileSystem extends FileSystemService
{
    // public $image_path = '@common/uploads/images';
    // public $image_url = 'http://image.core.com';
    public $cloud_name;
    public $api_key;
    public $api_secret;

    public  function saveToDisk($file, $fileModel)
    {
        $filePath = $this->getRelativePath($fileModel->id);  
        $option = [
            "folder" => $filePath, 
            "public_id" => $fileModel->id
        ];

        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
        return \Cloudinary\Uploader::upload($file->tempName, $option);
    }

    public function saveThumbnail($fileModel, $thumbnail) 
    {
        $sizes = explode("x", $thumbnail);
        if (count($sizes) < 2) {
            return;
        }
     
        $filePath = sprintf("%s/%s", $this->getRelativePath($fileModel), $thumbnail);        
        $thumbWidth = ArrayHelper::getValue($sizes, 0);
        $thumbHeight = ArrayHelper::getValue($sizes, 1);
        
        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
        $options = [
            "folder" => $filePath, 
            "public_id" => $fileModel->id
            "width" => $thumbWidth, 
            "height" => $thumbHeight, 
            "crop" => "limit"
        ];
        return \Cloudinary\Uploader::upload($this->get($fileModel), $options);
    }    

    public function get($fileModel, $thumbnail = null)
    {
        \Cloudinary::config(array(
            "cloud_name" => $this->cloud_name,
            "api_key" => $this->api_key,
            "api_secret" => $this->api_secret
        ));
        $source = sprintf("%s/%s", $this->getRelativePath($fileModel->id), $fileModel->id);
        $options = [];
        if ($thumbnail) {
            list ($width, $height) = explode("x", $thumbnail);
            $options = ["width" => $width, "height" => $height, "crop" => "crop"];
        }
        return cloudinary_url($source, $options);
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