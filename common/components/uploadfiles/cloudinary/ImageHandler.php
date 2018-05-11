<?php
namespace common\components\uploadfiles\cloudinary;

use common\components\uploadfiles\UploadFiles;
/**
 * Adapter to integrate Cloudinary functions to Yii
 *
 * @link https://cloudinary.com/documentation/php_integration
 */
class ImageHandler extends UploadFiles
{
    public $image_path = '@common/uploads/images';
    public $image_url = 'http://image.core.com';
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

    public function upload($file, $options = array())
    {
        return \Cloudinary\Uploader::upload($file, $options);
    }

    public function get($source, $options = array())
    {
        return cloudinary_url($source, $options);
    }


}