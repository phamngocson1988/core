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

    public function getImage() 
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function getImageUrl($size = null, $default = '/images/noimage.png')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }

    public function getCreator()
    {
        return $this->hasOne(User::className(), ['id' => 'created_by']);
    }

    public function getCreatorName()
    {
        $user = $this->creator;
        if ($user) {
            return $user->name;
        }
        return '';
    }
}
