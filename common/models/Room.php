<?php

namespace common\models;

use Yii;
use common\components\helpers\DateHelper;

/**
 * This is the model class for table "room".
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property int $image_id
 * @property int $price
 * @property string $status
 * @property string $created_at
 * @property int $created_by
 */
class Room extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%room}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'created_at', 'created_by'], 'required'],
            [['image_id', 'price', 'created_by'], 'integer'],
            [['status'], 'string'],
            [['created_at'], 'safe'],
            [['title'], 'string', 'max' => 50],
            [['description'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'title' => Yii::t('app', 'Title'),
            'description' => Yii::t('app', 'Descripition'),
            'image_id' => Yii::t('app', 'Image ID'),
            'price' => Yii::t('app', 'Price'),
            'status' => Yii::t('app', 'Status'),
            'created_at' => Yii::t('app', 'Created At'),
            'created_by' => Yii::t('app', 'Created By'),
        ];
    }

    public function getAvailables()
    {
        return $this->hasMany(RoomAvailable::className(), ['room_id' => 'id']);
    }

    public function getSeasons()
    {
        return $this->hasMany(RoomSeason::className(), ['room_id' => 'id']);
    }

    public function getPrice($date = null)
    {
        $price = $this->price;
        if ($date == null) $date = date('Y-m-d');
        foreach ((array)$this->seasons as $season) {
            if ($season->isInRange($date)) {
                $price = $season->price;
                break;
            }
        }
        return (int)$price;
    }

    public function getPriceInRange($from, $to)
    {
        $ranges = DateHelper::ranges($from, $to);
        $prices = [];
        foreach ($ranges as $date) {
            $prices[$date] = $this->getPrice($date);
        }
        return $price;
    }
}
