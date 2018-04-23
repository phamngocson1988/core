<?php
namespace common\components\uploadfiles\standard;

use common\components\uploadfiles\UploadFiles;
use common\components\helpers\FileHelper;
use Yii;

class Standard extends UploadFiles
{
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
            foreach ($uploadedFiles as $file) {
                $fileModel = $this->saveToDatabase($file);
                $this->saveToDisk($file, $fileModel);
            }
            $transaction->commit();
            return true;
        } catch (Exception $e) {
            $transaction->rollBack();
            return false;
        }
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
        $filePath = $this->getFilePath($fileModel);
        $file->saveAs($filePath);
	}

    protected function getFilePath($fileModel)
    {
        $fileDir = sprintf("%s/%s", $this->image_path, $this->getRelativePath($fileModel->id));
        FileHelper::createDirectory($fileDir);
        $filePath = sprintf("%s/%s.%s", $fileDir, $fileModel->getName(), $fileModel->getExtension());
    }
	

    
}