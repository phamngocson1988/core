<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

class Realestate extends \yii\db\ActiveRecord
{
	const STATUS_INCOMING = 'incoming';
    const STATUS_SELLING = 'selling';
    const STATUS_SOLDOUT = 'soldout';
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%realestate}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INCOMING => Yii::t('app', 'incoming'),
            self::STATUS_SELLING => Yii::t('app', 'selling'),
            self::STATUS_SOLDOUT => Yii::t('app', 'soldout'),
        ];
    }
}
