<?php
namespace common\components\filesystem;

use yii\base\DynamicModel;
use yii\web\UploadedFile;
use yii\helpers\FileHelper;
use Yii;

class FileSystemManager extends DynamicModel
{
    public $extensions = null;
    public $mimeTypes = null;
    public $maxFiles = 4;
    public $maxSize; //bytes
    public $default_image = '/images/noimage.png';

	public $file_class = \common\models\File::class;
    public $dependency; // instance of FileSystemService;

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

    public function save($name)
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
                $dependency->save($file, $fileModel);
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

    public function upload($name, $path = '', $includeSchema = false)
    {
        $files = [];
        try {
            $uploadedFiles = UploadedFile::getInstancesByName($name);
            $this->defineAttribute($name, $uploadedFiles);
            if (!$this->validate()) { 
                return false;
            }
            $dependency = $this->instanceDependency();
            foreach ($uploadedFiles as $file) {
                $files[] = $dependency->upload($file, $path, $includeSchema);
            }
            $this->undefineAttribute($name);
            return $files;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
    }   

    public function getUrl($fileModel)
    {
        $dependency = $this->instanceDependency();
        $fileUrl = $dependency->getUrl($fileModel);
        return $fileUrl;
    }

    public function getImageUrl($fileModel, $size = null) 
    {
        $dependency = $this->instanceDependency();
        $fileUrl = $dependency->getThumb($fileModel, $size);
        return $fileUrl;
    }

    public function getPath($fileModel)
    {
        $dependency = $this->instanceDependency();
        return $dependency->getPath($fileModel);
    }

    protected function saveToDatabase($file)
    {
        $fileModel = $this->instanceFileClass();
        $fileModel->name = $file->baseName;
        $fileModel->extension = $file->extension;
        $fileModel->size = $file->size;
        $fileModel->created_by = Yii::$app->user->id;
        $fileModel->save();
        return $fileModel;
    } 

    public function delete($fileModel)
    {
        $path = $this->getPath($fileModel);
        $this->unlink($path);
    }

    protected function unlink($path) 
    {
        if (!is_dir($path)) {
            echo sprintf("Delete File: %s\n", $path);
            FileHelper::unlink($path);
            $this->unlink(dirname($path));
        } else {
            $files = FileHelper::findFiles($path, ['recursive' => false]);
            $directories = FileHelper::findDirectories($path, ['recursive' => false]);
            if (!count($files) && !count($directories)) {
                echo sprintf("Delete folder: %s\n", $path);
                rmdir($path);
                $this->unlink(dirname($path));
            }
        }
    }
}