<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class ComplainFile extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%complain_file}}';
    }

    public function getFile()
    {
        return $this->hasOne(File::className(), ['id' => 'file_id']);
    }
}