<?php
namespace common\models;

use Yii;

class RoomService extends \yii\db\ActiveRecord
{
    public static function tableName()
    {
        return '{{%room_service}}';
    }

    public function rules()
    {
        return [
            [['room_id', 'realestate_service_id'], 'required'],
            [['price'], 'number'],
        ];
    }

    public function getRealestateService()
    {
        return $this->hasOne(RealestateService::className(), ['id', 'realestate_service_id']);
    }

    public function getPrice()
    {
        if ($this->price) return (int)$this->price;
        $service = $this->realestateService;
        return $service->price;
    }

    public function isApply()
    {
        return (boolean)$this->apply;
    }
}
