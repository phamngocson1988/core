<?php
namespace common\components\uploadfiles\standard;

use common\components\uploadfiles\UploadFiles;
use Yii;

class Standard extends UploadFiles
{
	public function uploadFileFromForm(UploadedFile $uploadedFile)
	{
		if (!$this->validate()) { 
            return false;
        }

        try {
            $transaction = Yii::$app->db->beginTransaction();
            $thumbnails = self::getThumbnails();
            foreach ($this->imageFiles as $file) {
                $fileModel = $this->saveToDatabase($file);
                $this->saveToDisk($file, $fileModel);
                


                // foreach ($thumbnails as $thumbSize) {
                //     $fileModel->saveThumb($thumbSize);
                // }
                // $this->_images[] = $fileModel;
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
	}



	protected function saveToDisk($file, $fileModel)
	{
        $absolutePath = $fileModel->getAbsolutePath($fileModel->id);
        FileHelper::createDirectory($absolutePath);
        $filePath = $this->getPath($fileModel);
        $file->saveAs($filePath);
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