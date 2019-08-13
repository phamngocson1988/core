<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class OrderFile extends ActiveRecord
{
    const TYPE_EVIDENCE_BEFORE = 'evidence_before';
    const TYPE_EVIDENCE_AFTER = 'evidence_after';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order_file}}';
    }

    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }

    public function getUrl()
    {
        $file = $this->file;
        if (!$file) return '';
        return $file->getUrl();
    }

    public function afterDelete()
    {
        $file = $this->file;
        $file->delete();
        parent::afterDelete();
    }
}