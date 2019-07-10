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
}
