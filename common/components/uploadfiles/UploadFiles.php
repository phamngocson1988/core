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
	public $image_path = 'C:/xampp/htdocs/core/common/uploads/images';
	public $file_path = 'C:/xampp/htdocs/core/common/uploads/files';
	public $thumbnails = ['100x100'];
	public $image_class = \common\models\Image::class;
	public $file_class = \common\models\Image::class;

    /**
     * @param array UploadedFile
     */
	public function uploadFileFromForm($uploadedFiles)
	{
		if (!$this->validate()) { 
            return false;
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $thumbnails = self::getThumbnails();
            foreach ($uploadedFiles as $file) {
                // Save database.
                $fileModel = new TImage();
                $fileModel->name = $file->baseName;
                $fileModel->extension = $file->extension;
                $fileModel->size = $file->size;
                $fileModel->created_at = strtotime('now');
                $fileModel->created_by = Yii::$app->user->id;
                $fileModel->save();

                // Save to disk
                $absolutePath = $fileModel->getAbsolutePath();
                FileHelper::createDirectory($absolutePath);
                $filePath = $fileModel->getPath();
                $file->saveAs($filePath);


                foreach ($thumbnails as $thumbSize) {
                    $fileModel->saveThumb($thumbSize);
                }
                $this->_images[] = $fileModel;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
	}

	public function uploadFileFromPath($path)
	{
		
	}

	public function getFile($path)
	{

	}

	protected function saveToDatabase($file)
	{
        $fileModel = $this->instanceImageClass();
        $fileModel->name = $file->baseName;
        $fileModel->extension = $file->extension;
        $fileModel->size = $file->size;
        $fileModel->created_at = strtotime('now');
        $fileModel->created_by = Yii::$app->user->id;
        $fileModel->save();
        return $fileModel;
	}

	protected function saveToDisk($file, $fileModel)
	{

	}

	protected function createThumb()
	{

	}

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