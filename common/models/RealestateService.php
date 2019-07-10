<?php
namespace common\models;

use Yii;

class RealestateService extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%realestate_service}}';
    }

    public function rules()
    {
        return [
            [['service_id', 'realestate_id'], 'required'],
            [['price'], 'number'],
        ];
    }

    public function getService()
    {
        return $this->hasOne(Service::className(), ['id' => 'service_id']);
    }
}
