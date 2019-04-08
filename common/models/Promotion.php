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
}
