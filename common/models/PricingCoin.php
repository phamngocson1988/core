<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

class PricingCoin extends ActiveRecord
{
	const IS_BEST = "Y";
    const IS_NOT_BEST = "N";

    CONST STATUS_VISIBLE = "Y";
    const STATUS_INVISIBLE = "N";

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
        ];
    }

	public static function tableName()
    {
        return '{{%pricing_coin}}';
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_VISIBLE => Yii::t('app', 'visible'),
            self::STATUS_INVISIBLE => Yii::t('app', 'invisible'),
        ];
    }

    public static function getTheBestStatusList()
    {
        return [
            self::IS_BEST => Yii::t('app', 'boolean_yes'),
            self::IS_NOT_BEST => Yii::t('app', 'boolean_no'),
        ];
    }

    public function isBest()
    {
        return $this->is_best == self::IS_BEST;
    }

    public function isVisible()
    {
        return $this->status == self::STATUS_VISIBLE;
    }
}