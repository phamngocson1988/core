<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\SluggableBehavior;

class Package extends ActiveRecord
{
    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';	
    
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

    public function attributeLabels() { 

        return  [
            'title' => Yii::t('app', 'title'),
            'description' => Yii::t('app', 'description'),
            'status' => Yii::t('app', 'status'),
            'num_of_coin' => Yii::t('app', 'num_of_coin'),
            'unit_name' => Yii::t('app', 'unit_name'),
            'amount' => Yii::t('app', 'amount'),
        ];
    }

    public function rules()
    {
        return [
            [['title', 'description'], 'trim'],
            [['title', 'num_of_coin', 'amount'], 'required'],
            ['status', 'in', 'range' => array_keys(self::getStatusList())],
        ];
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