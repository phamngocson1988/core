<?php

namespace common\models;

use Yii;

class Room extends \yii\db\ActiveRecord
{
    const SCENARIO_CREATE = 'SCENARIO_CREATE';
    const SCENARIO_EDIT = 'SCENARIO_EDIT';

    const STATUS_AVAILABLE = 'available';
    const STATUS_RENT = 'rent';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room}}';
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['code', 'title', 'realestate_id', 'content', 'image_id', 'price', 'status', 'area', 'bed', 'toilet', 'kitchen'],
            self::SCENARIO_EDIT => ['code', 'title', 'content', 'image_id', 'price', 'status', 'area', 'bed', 'toilet', 'kitchen'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'title'], 'required'],
            [['realestate_id'], 'required', 'on' => self::SCENARIO_CREATE],
            [['content', 'image_id', 'price', 'status', 'area', 'bed', 'toilet', 'kitchen'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'code' => 'Mã phòng',
            'title' => 'Tiêu đề',
            'content' => 'Mô tả',
            'image_id' => 'Hình ảnh',
            'price' => 'Giá thuê',
            'status' => 'Trạng thái',
            'apply' => 'Áp dụng',
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_AVAILABLE => 'Còn trống',
            self::STATUS_RENT => 'Đã cho thuê'
        ];
    }

    public function getRoomServices()
    {
        return $this->hasMany(RoomService::className(), ['room_id' => 'id']);
    }

    public function getAvailableRoomServices()
    {
        return $this->hasMany(RoomService::className(), ['room_id' => 'id'])->andOnCondition(['apply' => 1]);
    }

    public function getRealestate()
    {
        return $this->hasOne(Realestate::className(), ['id' => 'realestate_id']);
    }
}
