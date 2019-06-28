<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use common\models\Image;
use common\models\User;
use yii\behaviors\TimestampBehavior;
use yii\behaviors\BlameableBehavior;
use yii\helpers\ArrayHelper;

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

    const SCENARIO_BUY_GEMS = 'coin';
    const SCENARIO_BUY_COIN = 'money';

    const IS_VALID = 1;
    const IS_INVALID = 0;

    public $user_ids = [];
    public $game_ids = [];

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
            self::SCENARIO_CREATE => ['title', 'content', 'image_id', 'code', 'promotion_type', 'value', 'promotion_scenario', 'user_using', 'from_date', 'to_date', 'status', 'game_ids', 'user_ids', 'rule_name', 'rule_data'],
            self::SCENARIO_EDIT => ['id', 'title', 'content', 'image_id', 'promotion_type', 'value', 'promotion_scenario', 'user_using', 'from_date', 'to_date', 'status', 'game_ids', 'user_ids', 'rule_name', 'rule_data'],
        ];
    }

    public function rules()
    {
    	return [
    		[['title', 'code', 'promotion_type', 'value', 'promotion_scenario', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'title', 'code', 'promotion_type', 'value', 'promotion_scenario', 'status'], 'required', 'on' => self::SCENARIO_EDIT],
            [['user_using', 'from_date', 'to_date', 'content', 'image_id', 'game_ids', 'user_ids', 'rule_name', 'rule_data'], 'safe'],
            ['code', 'unique', 'targetClass' => '\common\models\Promotion', 'message' => 'Voucher code is duplicated'],
    	];
    }

    public static function  getRuleHandlers()
    {
        return [
            'total_using' => [
                'class' => '\common\models\promotions\TotalUsingRule',
                'title' => 'Limit the number of using this promotion'
            ],
            'user_using' => [
                'class' => '\common\models\promotions\UserUsingRule',
                'title' => 'Limit the number of using this promotion for 1 user'
            ],
            'specified_users' => [
                'class' => '\common\models\promotions\SpecifiedUsersRule',
                'title' => 'Khuyến mãi dành cho khách hàng cụ thể'
            ],
            'specified_games' => [
                'class' => '\common\models\promotions\SpecifiedGamesRule',
                'title' => 'Khuyến mãi dành cho game cụ thể'
            ]
        ];
    }

    // public static function  getBenefitHandlers()
    // {
    //     [
    //         'total_using' => [
    //             'class' => '\common\models\promotions\TotalUsingRule',
    //             'label' => 'Total of using',
    //             'title' => 'Limit the number of using this promotion'
    //         ],
    //         'user_using' => [
    //             'class' => '\common\models\promotions\UserUsingRule',
    //             'label' => 'Total of using for 1 user',
    //             'title' => 'Limit the number of using this promotion for 1 user'
    //         ]
    //     ];
    // }

    

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
        return ($this->promotion_direction == 'down') ? $this->calculateValue($amount) : 0;
    }

    public function calculateBenifit($amount)
    {
        return ($this->promotion_direction == 'up') ? $this->calculateValue($amount) : 0;
    }

    public function calculateValue($amount)
    {
        if ($this->promotion_type == self::TYPE_FIX) {
            return min($amount, $this->value);
        } elseif ($this->promotion_type == self::TYPE_PERCENT) {
            return ceil(($this->value * $amount) / 100);
        }
    }

    public function apply($userId)
    {
        $apply = new PromotionApply();
        $apply->promotion_id = $this->id;
        $apply->user_id = $userId;
        $apply->save();
        if ($this->total_using && $this->countApply() >= $this->total_using) {
            $this->is_valid = self::IS_INVALID;
            $this->save();
        } elseif ($this->user_using && $this->countApply($userId) >= $this->user_using) {
            $this->is_valid = self::IS_INVALID;
            $this->save();
        }
    }

    public function countApply($userId = null)
    {
        $command = PromotionApply::find()->where(['promotion_id' => $this->id]);
        if ($userId) { 
            $command->andWhere(['user_id' => $userId]);
        }
        return $command->count();
    }

    public function isValid($time = 'now')
    {
        if ($this->is_value == self::IS_INVALID) return fasle;
        $now = strtotime($time);
        if (!$this->from_date && !$this->to_date) return true;
        elseif ($this->from_date && $this->to_date) return ($now >= strtotime($this->from_date) && ($now <= strtotime($this->to_date)));
        elseif ($this->from_date) return ($now >= strtotime($this->from_date));
        elseif ($this->to_date) return ($now <= strtotime($this->to_date));
        return false;
    }

    public function isEnable()
    {
        return $this->status == self::STATUS_VISIBLE && $this->isValid();
    }

    public function getPromotionGames()
    {
        return $this->hasMany(PromotionGame::className(), ['promotion_id' => 'id']);
    }

    public function getPromotionUsers()
    {
        return $this->hasMany(PromotionUser::className(), ['promotion_id' => 'id']);
    }

    public static function findAvailable()
    {
        $command = self::find();
        $command->where(["IN", "status", [self::STATUS_VISIBLE, self::STATUS_INVISIBLE]]);
        $command->andWhere(["is_valid" => self::IS_VALID]);
        $now = date('Y-m-d');
        $command->andWhere(['OR', 
            ['<=', 'from_date', $now],
            ['from_date' => null]
        ]);
        $command->andWhere(['OR', 
            ['>=', 'to_date', $now],
            ['to_date' => null]
        ]);
        return $command;
    }

    public static function findValid()
    {
        $command = self::find();
        $command->where(["status" => self::STATUS_VISIBLE]);
        $command->andWhere(["is_valid" => self::IS_VALID]);
        $now = date('Y-m-d');
        $command->andWhere(['OR', 
            ['<=', 'from_date', $now],
            ['from_date' => null]
        ]);
        $command->andWhere(['OR', 
            ['>=', 'to_date', $now],
            ['to_date' => null]
        ]);
        return $command;
    }

    public static function pickRule($ruleName)
    {
        if (!$ruleName) return null;
        $handlers = self::getRuleHandlers();
        $handler = ArrayHelper::getValue($handlers, $ruleName);
        if (!$handler) return null;
        return Yii::createObject($handler);
    }

    public function getRule()
    {
        $rule = self::pickRule($this->rule_name);
        if (!$rule) return null;
        $attrs = $this->getRuleData();
        if (!$attrs) return null;
        $rule->attributes = $attrs;
        return $rule;
    }

    public function addRule($rule)
    {
        $this->setRuleData($rule);
        $this->save();
    }

    public function setRuleData($rule)
    {
        $ruleData = $rule->asArray();
        $this->rule_data = serialize($ruleData);
    }

    public function getRuleData()
    {
        return @unserialize($this->rule_data);
    }
}
