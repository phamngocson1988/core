<?php
namespace common\components\filesystem;

use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\imagine\Image;
use common\models\Image as TImage;
use yii\helpers\ArrayHelper;
use common\components\helpers\FileHelper;
use Yii;

class FileSystemManager extends DynamicModel
{
    public $thumbnails = ['50x50', '100x100', '150x150', '300x300', '500x500', '940x630', '800x800', '1000x1000'];
    public $extensions = ["gif", "jpeg", "jpg", "png", "svg", "blob"];
    public $mimeTypes = ["image/gif", "image/jpeg", "image/pjpeg", "image/x-png", "image/png", "image/svg+xml"];
    public $maxFiles = 4;
    public $maxSize; //bytes
    public $default_image = '/images/noimage.png';
    public $generate_thumbnail = true;

	public $image_class = \common\models\Image::class;
    public $dependency; // instance of FileSystemService;

	protected function instanceImageClass()
	{
		return Yii::createObject($this->image_class);
	}

	protected function instanceFileClass()
	{
		return Yii::createObject($this->file_class);
	}

    protected function instanceDependency()
    {
        return Yii::createObject($this->dependency);
    }

    public function init()
    {
        if (!$this->dependency) die('No file system declared');
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

    public function upload($name)
    {
        $files = [];
        try {
            $uploadedFiles = UploadedFile::getInstancesByName($name);
            $this->defineAttribute($name, $uploadedFiles);
            if (!$this->validate()) { 
                return false;
            }
            $transaction = Yii::$app->db->beginTransaction();
            $dependency = $this->instanceDependency();
            foreach ($uploadedFiles as $file) {
                $fileModel = $this->saveToDatabase($file);
                $dependency->saveImage($file, $fileModel);
                if (!$this->generate_thumbnail) continue;
                foreach ($this->thumbnails as $thumbnail) {
                    $dependency->saveThumbnail($fileModel, $thumbnail);
                }
                $files[] = $fileModel;
            }
            $transaction->commit();
            $this->undefineAttribute($name);
            return $files;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }   

    public function get($fileModel, $thumbnail = null)
    {
        $dependency = $this->instanceDependency();
        if (!$this->generate_thumbnail) $thumbnail = null;
        $fileUrl = $dependency->get($fileModel, $thumbnail);
        return $fileUrl;
    }

    protected function saveToDatabase($file)
    {
        $fileModel = $this->instanceImageClass();
        $fileModel->name = $file->baseName;
        $fileModel->extension = $file->extension;
        $fileModel->size = $file->size;
        $fileModel->created_by = Yii::$app->user->id;
        $fileModel->save();
        return $fileModel;
    } 
}