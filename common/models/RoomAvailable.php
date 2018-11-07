<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%room_available}}".
 *
 * @property int $id
 * @property int $room_id
 * @property string $from_date
 * @property string $to_date
 * @property int $available
 */
class RoomAvailable extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room_available}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['room_id', 'available'], 'integer'],
            [['from_date', 'to_date'], 'safe'],
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
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'available' => Yii::t('app', 'Available'),
        ];
    }
}
