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

    const SCENARIO_BUY_GEMS = 'gems';
    const SCENARIO_BUY_COIN = 'coin';

    const IS_VALID = 1;
    const IS_INVALID = 0;

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
            self::SCENARIO_CREATE => ['title', 'content', 'image_id', 'code', 'promotion_scenario', 'user_using', 'from_date', 'to_date', 'status', 'rule_name', 'benefit_name'],
            self::SCENARIO_EDIT => ['id', 'title', 'content', 'image_id', 'promotion_scenario', 'user_using', 'from_date', 'to_date', 'status', 'rule_name', 'benefit_name'],
        ];
    }

    public function rules()
    {
    	return [
    		[['title', 'code', 'promotion_scenario', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            ['code', 'unique', 'targetClass' => '\common\models\Promotion', 'message' => 'Voucher code is duplicated', 'on' => self::SCENARIO_CREATE],
            [['id', 'title', 'promotion_scenario', 'status'], 'required', 'on' => self::SCENARIO_EDIT],
            [['user_using', 'from_date', 'to_date', 'content', 'image_id', 'rule_name', 'benefit_name'], 'safe'],
    	];
    }

    public static function  getRuleHandlers()
    {
        return [
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

    public static function  getBenefitHandlers($type = null)
    {
        $list = [
            'promotion_coin' => [
                'class' => '\common\models\promotions\PromotionCoinBenefit',
                'title' => 'Gift Kingcoin',
                'promotion_scenario' => self::SCENARIO_BUY_COIN
            ],
            'promotion_unit' => [
                'class' => '\common\models\promotions\PromotionUnitBenefit',
                'title' => 'Gift Game Unit',
                'promotion_scenario' => self::SCENARIO_BUY_GEMS
            ],
        ];
        if (!$type) return $list;
        return array_filter($list, function($benefit) use ($type) {
            return $benefit['promotion_scenario'] == $type;
        });
    }

    

    public static function getStatusList()
    {
        return [
            self::STATUS_INVISIBLE => 'Invisible',
            self::STATUS_VISIBLE => 'Visible',
            // self::STATUS_DELETE => 'Deleted'
        ];
    }

    // Check validate
    public function canApplyForUser($userId)
    {
        if (!$this->isEnable()) return false;
        $rule = $this->getRule();
        if (!$rule) return true;
        if ($rule->getEffectedObject() != 'user') return true;
        return $rule->isValid($userId);
    }

    public function canApplyForGame($gameId)
    {
        if (!$this->isEnable()) return false;
        $rule = $this->getRule();
        if (!$rule) return true;
        if ($rule->getEffectedObject() != 'game') return true;
        return $rule->isValid($gameId);
    }

    // Apply
    public function apply($amount)
    {
        $benefit = $this->getBenefit();
        if (!$benefit) return 0;
        return $benefit->apply($amount);
    }

    // Validate a promotion is valid
    public function isEnable()
    {
        // Check status
        if ($this->status != self::STATUS_VISIBLE) return false;

        // Check time
        $now = strtotime('now');
        $from = ($this->from_date) ? strtotime($this->from_date) : null;
        $to = ($this->to_date) ? strtotime($this->to_date) : null;
        if (!$from && !$to) return true;
        elseif ($from && $to) return $now >= $from && $now <= $to;
        elseif ($from) return $now >= $from;
        elseif ($to) return $now <= $to;
        return false;
    }

    // Promotion rule
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
        $attrs['promotion_id'] = $this->id;
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

    // Benefit
    public static function pickBenefit($benefitName)
    {
        if (!$benefitName) return null;
        $handlers = self::getBenefitHandlers();
        $handler = ArrayHelper::getValue($handlers, $benefitName);
        if (!$handler) return null;
        return Yii::createObject($handler);
    }

    public function getBenefit()
    {
        $benefit = self::pickBenefit($this->benefit_name);
        if (!$benefit) return null;
        $attrs = $this->getBenefitData();
        if (!$attrs) return null;
        $attrs['promotion_id'] = $this->id;
        $benefit->attributes = $attrs;
        return $benefit;
    }

    public function addBenefit($benefit)
    {
        $this->setBenefitData($benefit);
        $this->save();
    }

    public function setBenefitData($benefit)
    {
        $benefitData = $benefit->asArray();
        $this->benefit_data = serialize($benefitData);
    }

    public function getBenefitData()
    {
        return @unserialize($this->benefit_data);
    }
}
