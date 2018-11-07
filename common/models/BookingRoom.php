<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%booking_room}}".
 *
 * @property int $id
 * @property int $booking_id
 * @property string $booking_date
 * @property int $room_identify
 * @property int $sub_price
 * @property int $total_price
 * @property string $note
 */
class BookingRoom extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%booking_room}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['booking_id', 'booking_date', 'room_identify'], 'required'],
            [['booking_id', 'room_identify', 'sub_price', 'total_price'], 'integer'],
            [['booking_date'], 'safe'],
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
            'booking_id' => Yii::t('app', 'Booking ID'),
            'booking_date' => Yii::t('app', 'Booking Date'),
            'room_identify' => Yii::t('app', 'Room Identify'),
            'sub_price' => Yii::t('app', 'Sub Price'),
            'total_price' => Yii::t('app', 'Total Price'),
            'note' => Yii::t('app', 'Note'),
        ];
    }
}
