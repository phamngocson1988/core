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

    const DIRECTION_D = 'D';
    const DIRECTION_T = 'T';
    const DIRECTION_N = 'N';
    const DIRECTION_B = 'B';
    public $service_id;
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

    public static function getDirectionList()
    {
        return [
            self::DIRECTION_D => Yii::t('app', 'east'),
            self::DIRECTION_T => Yii::t('app', 'west'),
            self::DIRECTION_N => Yii::t('app', 'south'),
            self::DIRECTION_B => Yii::t('app', 'north'),
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

    public function getGallery()
    {
        return $this->hasMany(Image::className(), ['id' => 'image_id'])
            ->viaTable(RealestateImage::tableName(), ['realestate_id' => 'id']);
    }

    public function getMetaTitle()
    {
        return ($this->meta_title) ? $this->meta_title : $this->title;
    }

    public function getMetaDescription()
    {
        return ($this->meta_description) ? $this->meta_description : $this->title;
    }

    public function getMetaKeyword()
    {
        return $this->meta_keyword;
    }    

    public function getExcerpt($number = null) 
    {
        $excerpt = ($this->excerpt) ? $this->excerpt : strip_tags($this->content);
        if ($number && !$this->excerpt) {
            return substr($excerpt, 0, $number);
        }
        return $excerpt;
    }  

    public function getServices()
    {
        return $this->hasMany(Service::className(), ['id' => 'service_id'])
            ->viaTable(RealestateService::tableName(), ['realestate_id' => 'id']);
    }

    public function getRealestateServices()
    {
        return $this->hasMany(RealestateService::className(), ['realestate_id' => 'id']);
    }
}
