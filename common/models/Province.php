<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;

class Province extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%province}}';
    }

    public function getCities()
    {
        return $this->hasMany(City::className(), ['province_id' => 'id']);
    }
}
