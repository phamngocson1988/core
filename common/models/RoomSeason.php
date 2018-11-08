<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_season}}".
 *
 * @property int $id
 * @property int $room_id
 * @property int $season_id
 * @property int $price
 * @property string $status Enum: Y,N
 * @property string $note
 */
class RoomSeason extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room_season}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id'], 'required'],
            [['room_id', 'season_id', 'price'], 'integer'],
            [['status'], 'string', 'max' => 1],
            [['note'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'room_id' => Yii::t('app', 'Room ID'),
            'season_id' => Yii::t('app', 'Season ID'),
            'price' => Yii::t('app', 'Price'),
            'status' => Yii::t('app', 'Status'),
            'note' => Yii::t('app', 'Note'),
        ];
    }

    public function getSeason()
    {
        return $this->hasOne(Season::className(), ['id' => 'season_id']);
    }

    public function isInRange($date)
    {
        return $this->season->isInRange($date);
    }
}
