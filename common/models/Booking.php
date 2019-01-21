<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "{{%booking}}".
 *
 * @property int $id
 * @property string $from_date
 * @property string $to_date
 * @property int $customer_id
 * @property string $customer_name
 * @property string $customer_email
 * @property string $customer_phone
 * @property string $customer_identify
 * @property int $total_price
 * @property int $total_paid
 * @property string $booking_status
 * @property string $payment_status
 * @property string $note
 * @property string $created_at
 * @property int $created_by
 */
class Booking extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%booking}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['from_date', 'to_date', 'customer_name', 'customer_phone', 'created_at', 'created_by'], 'required'],
            [['from_date', 'to_date', 'created_at'], 'safe'],
            [['customer_id', 'total_price', 'total_paid', 'created_by'], 'integer'],
            [['booking_status', 'payment_status'], 'string'],
            [['customer_name', 'customer_email'], 'string', 'max' => 100],
            [['customer_phone', 'customer_identify'], 'string', 'max' => 50],
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
            'from_date' => Yii::t('app', 'From Date'),
            'to_date' => Yii::t('app', 'To Date'),
            'customer_id' => Yii::t('app', 'Customer ID'),
            'customer_name' => Yii::t('app', 'Customer Name'),
            'customer_email' => Yii::t('app', 'Customer Email'),
            'customer_phone' => Yii::t('app', 'Customer Phone'),
            'customer_identify' => Yii::t('app', 'Customer Identify'),
            'total_price' => Yii::t('app', 'Total Price'),
            'total_paid' => Yii::t('app', 'Total Paid'),
            'booking_status' => Yii::t('app', 'Booking Status'),
            'payment_status' => Yii::t('app', 'Payment Status'),
            'note' => Yii::t('app', 'Note'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }
}
