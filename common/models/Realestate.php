<?php

namespace common\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use yii\helpers\ArrayHelper;

class Realestate extends \yii\db\ActiveRecord
{
    // const SCENARIO_CREATE = 'SCENARIO_CREATE';
    // const SCENARIO_EDIT = 'SCENARIO_EDIT';

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

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'image_id' => Yii::t('app', 'image'),
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
            'gallery' => Yii::t('app', 'gallery'),
        ];
    }

    public function rules()
    {
        return [
            [['title', 'address'], 'required'],
            [['excerpt', 'content', 'image_id', 'meta_title', 'meta_keyword', 'meta_description', 'gallery', 'latitude', 'longitude', 'electric_name', 'electric_data', 'water_name', 'water_data'], 'safe']
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

    public static function  getElectricHandlers()
    {
        return [
            'electric_standard' => [
                'class' => '\common\models\realestate\ElectricStandard',
                'title' => 'Tính theo giá nhà nước'
            ],
            'electric_fix' => [
                'class' => '\common\models\realestate\ElectricFix',
                'title' => 'Tính theo giá cố định theo tháng'
            ],
            'electric_per_people' => [
                'class' => '\common\models\realestate\ElectricPerPeople',
                'title' => 'Tính theo đầu người'
            ],
        ];
    }

    public static function  getWaterHandlers()
    {
        return [
            'water_standard' => [
                'class' => '\common\models\realestate\WaterStandard',
                'title' => 'Tính theo giá nhà nước'
            ],
            'water_fix' => [
                'class' => '\common\models\realestate\WaterFix',
                'title' => 'Tính theo giá cố định theo tháng'
            ],
            'water_per_people' => [
                'class' => '\common\models\realestate\WaterPerPeople',
                'title' => 'Tính theo đầu người'
            ]
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

    public function getRooms()
    {
        return $this->hasMany(Room::className(), ['realestate_id' => 'id']);
    }

    // Electric fee
    public static function pickElectric($electricName)
    {
        if (!$electricName) return null;
        $handlers = self::getElectricHandlers();
        $handler = ArrayHelper::getValue($handlers, $electricName);
        if (!$handler) return null;
        return Yii::createObject($handler);
    }

    public function getElectric()
    {
        $electric = self::pickElectric($this->electric_name);
        if (!$electric) return null;
        $attrs = $this->getElectricData();
        if (!$attrs) return null;
        $attrs['promotion_id'] = $this->id;
        $electric->attributes = $attrs;
        return $electric;
    }

    public function addElectric($electric)
    {
        $this->setElectricData($electric);
        $this->save();
    }

    public function setElectricData($electric)
    {
        $electricData = $electric->asArray();
        $this->electric_data = serialize($electricData);
    }

    public function getElectricData()
    {
        return @unserialize($this->electric_data);
    }

    // Water
    public static function pickWater($waterName)
    {
        if (!$waterName) return null;
        $handlers = self::getWaterHandlers();
        $handler = ArrayHelper::getValue($handlers, $waterName);
        if (!$handler) return null;
        return Yii::createObject($handler);
    }

    public function getWater()
    {
        $water = self::pickWater($this->water_name);
        if (!$water) return null;
        $attrs = $this->getWaterData();
        if (!$attrs) return null;
        $attrs['promotion_id'] = $this->id;
        $water->attributes = $attrs;
        return $water;
    }

    public function addWater($water)
    {
        $this->setWaterData($water);
        $this->save();
    }

    public function setWaterData($water)
    {
        $waterData = $water->asArray();
        $this->water_data = serialize($waterData);
    }

    public function getWaterData()
    {
        return @unserialize($this->water_data);
    }
}
