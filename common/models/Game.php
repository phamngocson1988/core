<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\User;
use common\models\Image;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use common\behaviors\GamePriceLogBehavior;
use common\behaviors\GameCategoryBehavior;

class Game extends ActiveRecord
{
	const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    const STATUS_DELETE = 'D';

    const UNPIN = 0;
    const PIN = 1;

    const HOT_DEAL = 1;
    const TOP_GROSSING = 1;
    const NEW_TRENDING = 1;
    const BACKTOSTOCK = 1;

    const SOLDOUT = 1;
    const INSTOCK = 0;

    const AUTO_DISPATCHER_OFF = 0;
    const AUTO_DISPATCHER_ON = 1;
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%game}}';
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
            ['class' => GamePriceLogBehavior::className()],
            ['class' => GameCategoryBehavior::className()],
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_VISIBLE => 'Visible',
            self::STATUS_DELETE => 'Deleted'
        ];
    }

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'excerpt' => Yii::t('app', 'excerpt'),
            'unit_name' => Yii::t('app', 'unit_name'),
            'image_id' => Yii::t('app', 'image'),
            'price' => 'Giá bán',
            'meta_title' => Yii::t('app', 'meta_title'),
            'meta_keyword' => Yii::t('app', 'meta_keyword'),
            'meta_description' => Yii::t('app', 'meta_description'),
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

    public function getShortTitle()
    {
        return ($this->short_title) ? $this->short_title : $this->title;
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

    public function getCogs() // Cost of goods sold: giá vốn
    {
        return round(array_sum([$this->price1, $this->price2, $this->price3]) / 3, 1);
    }

    public function getPrice()
    {
        $cogs = $this->getCogs();
        $desired_profit = Yii::$app->settings->get('ApplicationSettingForm', 'desired_profit', 0);
        $managing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'managing_cost_rate', 0);
        $investing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'investing_cost_rate', 0);
        /** 
         * Change requirement  from Leo
         * Giá lẻ= giá thu vào + BDLN lẻ  mong muốn
         * @date 2020-05-04
         */
        // $price = ($cogs + $desired_profit) * (100 + $managing_cost_rate + $investing_cost_rate) / 100;
        $price = $cogs + $desired_profit;
        return round($price, 1);
    }   

    public function getResellerPrice($level = User::RESELLER_LEVEL_1)
    {
        $cogs = $this->getCogs();
        $desired_profit = Yii::$app->settings->get('ApplicationSettingForm', 'reseller_desired_profit', 0);
        $managing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'managing_cost_rate', 0);
        $investing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'investing_cost_rate', 0);
        /** 
         * Change requirement  from Leo
         * Giá lẻ= giá thu vào + BDLN lẻ  mong muốn
         * @date 2020-05-04
         */
        // $price = ($cogs + $desired_profit) * (100 + $managing_cost_rate + $investing_cost_rate) / 100;
        $price = $cogs + $desired_profit;
        $price = round($price, 1);
        if ($level == User::RESELLER_LEVEL_1) return $price + 4;
        if ($level == User::RESELLER_LEVEL_3) return $price - 1.5;
        return $price;
    }

    public function getOriginalPrice()
    {
        return ($this->original_price) ? $this->original_price : $this->getPrice();
    }

    public function getSavedPrice()
    {
        $ori = $this->getOriginalPrice();
        $price = $this->getPrice();
        if (!$ori) return 0;
        if ($ori && $ori <= $price) return 0;
        return round(($ori - $price) * 100 / $ori, 2);
    }

    public function getImages()
    {
        return $this->hasMany(GameImage::className(), ['game_id' => 'id']);
    }

    public function isSoldout()
    {
        return $this->soldout == self::SOLDOUT;
    }

    public function isVisible()
    {
        return $this->status == self::STATUS_VISIBLE;
    }

    public function isInvisible()
    {
        return $this->status == self::STATUS_INVISIBLE;
    }

    public function isDeleted()
    {
        return $this->status == self::STATUS_DELETE;
    }

    public function isBackToStock()
    {
        return $this->back_to_stock === self::BACKTOSTOCK;
    }

    public function isAutoDispatcher() 
    {
        return $this->auto_dispatcher === self::AUTO_DISPATCHER_ON;
    }
}
