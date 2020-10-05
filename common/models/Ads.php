<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\TimestampBehavior;

class Ads extends ActiveRecord
{
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const POSITION_TOPHOME = 'tophome';
    const POSITION_BOTTOMHOME = 'bottomhome';
    const POSITION_BANNERHOME = 'bannerhome';
    const POSITION_SIDEBAR = 'sidebar';

    public static function tableName()
    {
        return '{{%ads}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => 'updated_at',
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => 'updated_by',
            ],
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'active'),
            self::STATUS_INACTIVE => Yii::t('app', 'inactive'),
        ];
    }

    public static function getPositionList()
    {
        return [
            self::POSITION_TOPHOME => 'Top home (1260x60)',
            self::POSITION_BOTTOMHOME => 'Bottom home (1260x100)',
            self::POSITION_BANNERHOME => 'Banner home (600x400)',
            self::POSITION_SIDEBAR => 'Sidebar (220x700)',
        ];
    }

    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'media_id']);
    }

    public function getImageUrl($size = null, $default = 'https://www.ira-sme.net/wp-content/themes/consultix/images/no-image-found-360x260.png')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }

    public function getPosition()
    {
        $list = self::getPositionList();
        return ArrayHelper::getValue($list, $this->position);
    }

    public function isValid()
    {
        if ($this->status != self::STATUS_ACTIVE) return false;
        $now = strtotime('now');
        $start = strtotime($this->start_date);
        $end = strtotime($this->end_date);
        if ($now < $start) return false;
        if ($now > $end) return false;
        return true;
    }
}