<?php
namespace common\components\uploadfiles;

use yii\base\Model;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\models\Image as TImage;
use yii\helpers\ArrayHelper;
use common\components\helpers\FileHelper;
use Yii;


class UploadFiles extends Model
{
	public $thumbnails = ['50x50', '100x100', '150x150', '300x300', '500x500', '940x630', '800x800', '1000x1000'];
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
}