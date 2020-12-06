<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;
use common\behaviors\OperatorReviewBehavior;
use common\behaviors\OperatorComplainBehavior;
use common\behaviors\OperatorBonusBehavior;
use common\behaviors\OperatorStaffBehavior;

class Operator extends ActiveRecord
{
	const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;

    public static function tableName()
    {
        return '{{%operator}}';
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
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
            'operatorReview' => OperatorReviewBehavior::className(),
            'operatorComplain' => OperatorComplainBehavior::className(),
            'operatorBonus' => OperatorBonusBehavior::className(),
            'operatorStaff' => OperatorStaffBehavior::className(),
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'Active'),
            self::STATUS_INACTIVE => Yii::t('app', 'Inactive'),
            self::STATUS_DELETED => Yii::t('app', 'Deleted'),
        ];
    }

    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'logo']);
    }

    public function getImageUrl($size = null, $default = 'https://www.ira-sme.net/wp-content/themes/consultix/images/no-image-found-360x260.png')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }
}