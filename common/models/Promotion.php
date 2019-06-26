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

    const SCENARIO_BUY_GEMS = 'coin';
    const SCENARIO_BUY_COIN = 'money';

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
            self::SCENARIO_CREATE => ['title', 'content', 'image_id', 'code', 'promotion_type', 'value', 'promotion_scenario', 'user_using', 'from_date', 'to_date', 'status', 'game_ids', 'user_ids'],
            self::SCENARIO_EDIT => ['id', 'title', 'content', 'image_id', 'code', 'promotion_type', 'value', 'promotion_scenario', 'user_using', 'from_date', 'to_date', 'status', 'game_ids', 'user_ids'],
        ];
    }

    public function rules()
    {
    	return [
    		[['title', 'code', 'promotion_type', 'value', 'promotion_scenario', 'status'], 'required', 'on' => self::SCENARIO_CREATE],
            [['id', 'title', 'code', 'promotion_type', 'value', 'promotion_scenario', 'status'], 'required', 'on' => self::SCENARIO_EDIT],
            [['user_using', 'from_date', 'to_date', 'content', 'image_id', 'game_ids', 'user_ids'], 'safe'],
            ['code', 'unique', 'targetClass' => '\common\models\Promotion', 'message' => 'Voucher code is duplicated'],
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
        if ($this->promotion_type == self::TYPE_FIX) {
            return min($amount, $this->value);
        } elseif ($this->promotion_type == self::TYPE_PERCENT) {
            return ceil(($this->value * $amount) / 100);
        }
    }

    public function calculateBenifit($amount)
    {
        if ($this->promotion_type == self::TYPE_FIX) {
            return min($amount, $this->value);
        } elseif ($this->promotion_type == self::TYPE_PERCENT) {
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

    public function canApplyGame($gameId)
    {
        $games = $this->promotionGames;
        // Apply for all games
        if (empty($games)) return true;
        // Apply for current game
        else {
            $gameIds = array_column($games, 'game_id');
            return in_array($gameId, $gameIds);
        }
    }

    public function canApplyUser($userId)
    {
        $users = $this->promotionUsers;
        // Apply for all users
        if (empty($users)) return true;
        // Apply for current user
        else {
            $userIds = array_column($users, 'user_id');
            return in_array($userId, $userIds);
        }
    }
}
