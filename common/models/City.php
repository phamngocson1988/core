<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class City extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%city}}';
    }

    public function getWards()
    {
        return $this->hasMany(Ward::className(), ['city_id' => 'id']);
    }
}
