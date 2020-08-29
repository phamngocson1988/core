<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\behaviors\BlameableBehavior;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

class Bonus extends ActiveRecord
{
	const STATUS_DELETED = 0;
    const STATUS_INACTIVE = 1;
    const STATUS_ACTIVE = 10;

    const TYPE_WELCOME = 'welcome';
    const TYPE_RELOAD = 'reload';
    const TYPE_NODEPOSIT = 'no_deposit';

    public static function tableName()
    {
        return '{{%bonus}}';
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
                'attribute' => 'title',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique'=>true,
            ],
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_ACTIVE => Yii::t('app', 'active'),
            self::STATUS_INACTIVE => Yii::t('app', 'inactive'),
            self::STATUS_DELETED => Yii::t('app', 'disable'),
        ];
    }

    public static function getTypeList()
    {
        return [
            self::TYPE_WELCOME => 'Welcome Bonus',
            self::TYPE_RELOAD => 'Reload Bonus',
            self::TYPE_NODEPOSIT => 'No Deposit Bonus',
        ];
    }

    public function getOperator()
    {
        return $this->hasOne(Operator::className(), ['id' => 'operator_id']);
    }

    public function getImage()
    {
        return $this->hasOne(Image::className(), ['id' => 'image_id']);
    }

    public function getImageUrl($size = null, $default = 'https://www.ira-sme.net/wp-content/themes/consultix/images/no-image-found-360x260.png')
    {
        $image = $this->image;
        if ($image) {
            return $image->getUrl($size);
        }
        return $default;
    }

    public function getType()
    {
        $list = self::getTypeList();
        return ArrayHelper::getValue($list, $this->bonus_type);
    }
}