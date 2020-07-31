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
            self::POSITION_TOPHOME => 'Top home',
            self::POSITION_BANNERHOME => 'Home banner',
            self::POSITION_SIDEBAR => 'Sidebar',
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
        return ArrayHelper::getValue($list, $this->status);
    }
}