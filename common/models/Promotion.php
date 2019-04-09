<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Image;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;

/**
 * Promotion model
 */
class Promotion extends ActiveRecord
{
    const STATUS_INVISIBLE = 'N';
    const STATUS_VISIBLE = 'Y';
    // const STATUS_DELETE = 'D';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_EDIT = 'edit';

    const TYPE_FIX = 'fix';
    const TYPE_PERCENT = 'percent';

    const OBJECT_COIN = 'coin';
    const OBJECT_MONEY = 'money';

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%promotion}}';
    }

    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'createdAtAttribute' => 'created_at',
                'updatedAtAttribute' => false,
                'value' => date('Y-m-d H:i:s')
            ],
            [
                'class' => BlameableBehavior::className(),
                'createdByAttribute' => 'created_by',
                'updatedByAttribute' => false,
            ],
        ];
    }

    public function scenarios()
    {
        return [
            self::SCENARIO_CREATE => ['title', 'code', 'value_type', 'value', 'object_type', 'number_of_use', 'from_date', 'to_date', 'status'],
            self::SCENARIO_EDIT => ['id', 'title', 'code', 'value_type', 'value', 'object_type', 'number_of_use', 'from_date', 'to_date', 'status'],
        ];
    }

    public function rules()
    {
    	return [
    		[['title', 'code', 'value_type', 'value', 'object_type', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'title', 'code', 'value_type', 'value', 'object_type', 'status'], 'required', 'on' => self::SCENARIO_EDIT],
            [['number_of_use', 'from_date', 'to_date'], 'safe']
    	];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_VISIBLE => 'Visible',
            // self::STATUS_DELETE => 'Deleted'
        ];
    }

    public function calculateDiscount($amount)
    {
        if ($this->value_type == self::TYPE_FIX) {
            return min($amount, $this->value);
        } elseif ($this->value_type == self::TYPE_PERCENT) {
            return ceil(($this->value * $amount) / 100);
        }
    }

    public function isValid($time = 'now')
    {
        $now = strtotime($time);
        if (!$this->from_date && !$this->to_date) return true;
        elseif ($this->from_date && $this->to_date) return ($now >= strtotime($this->from_date) && ($now <= strtotime($this->to_date)));
        elseif ($this->from_date) return ($now >= strtotime($this->from_date));
        elseif ($this->to_date) return ($now <= strtotime($this->to_date));
        return false;
    }

    public function isEnable()
    {
        return $this->status == self::STATUS_VISIBLE;
    }
}
