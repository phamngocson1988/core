<?php
namespace common\components\uploadfiles;

use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\models\Image as TImage;
use yii\helpers\ArrayHelper;
use common\components\helpers\FileHelper;
use Yii;

class UploadFiles extends DynamicModel
{
    public $thumbnails = ['50x50', '100x100', '150x150', '300x300', '500x500', '940x630', '800x800', '1000x1000'];
    public $extensions = ["gif", "jpeg", "jpg", "png", "svg", "blob"];
    public $mimeTypes = ["image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/svg+xml"];
    public $maxFiles = 4;
    public $maxSize; //bytes
    public $default_image = '/images/noimage.png';
	public $image_class = \common\models\Image::class;
	public $file_class = \common\models\Image::class;

	protected function instanceImageClass()
	{
		return Yii::createObject($this->image_class);
	}

	protected function instanceFileClass()
	{
		return Yii::createObject($this->file_class);
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

    public function validate($attributeNames = NULL, $clearErrors = true)
    {
        $attributes = $this->attributes();
        if (!$attributes) {
            return true;
        }
        $this->addRule($attributes, 'file', [
            'skipOnEmpty' => false, 
            'extensions' => $this->extensions, 
            'maxFiles' => $this->maxFiles, 
            'maxSize' => $this->maxSize,
            'mimeTypes' => $this->mimeTypes,
        ]);
        return parent::validate($attributeNames, $clearErrors);
    }

    public function getDefaultImageUrl($url = null)
    {
        return ($url) ? $url : $this->default_image;
    }
}