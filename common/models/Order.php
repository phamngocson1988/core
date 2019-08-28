<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use common\models\User;
use common\models\Customer;
use common\models\OrderItems;
use common\models\OrderComments;
use common\models\OrderComplains;
use yii\behaviors\TimestampBehavior;

/**
 * Order model
 */
class Order extends ActiveRecord
{
    const STATUS_VERIFYING = 'verifying';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
    const STATUS_DELETED = 'deleted';

    const SCENARIO_CREATE = 'create';
    const SCENARIO_VERIFYING = self::STATUS_VERIFYING;
    const SCENARIO_PENDING = self::STATUS_PENDING;
    const SCENARIO_PROCESSING = self::STATUS_PROCESSING;
    const SCENARIO_COMPLETED = self::STATUS_COMPLETED;
    const SCENARIO_DELETED = self::STATUS_DELETED;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%order}}';
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
        ];
    }

    public static function getStatusList()
    {
        return [
            self::STATUS_VERIFYING => 'Verifying',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_DELETED => 'Deleted'
        ];
    }

    public static function getLoginMethodList()
    {
        return [
            'account' => 'Game account',
            'android' => 'Android',
            'ios' => 'Ios', 

        ];
    }

    public function getLoginMethod()
    {
        $list = self::getLoginMethodList();
        return ArrayHelper::getValue($list, $this->login_method);
    }

    public function getSaler()
    {
        return $this->hasOne(User::className(), ['id' => 'saler_id']);
    }

    public function getOrderteam()
    {
        return $this->hasOne(User::className(), ['id' => 'orderteam_id']);
    }

    public function getCustomer()
    {
        return $this->hasOne(User::className(), ['id' => 'customer_id']);
    }

    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getItems() 
    {
        return $this->hasMany(OrderItems::className(), ['order_id' => 'id']);
    }

    public function getFiles() 
    {
        return $this->hasMany(OrderFile::className(), ['order_id' => 'id']);
    }

    public function getEvidences() 
    {
        return $this->hasMany(OrderFile::className(), ['order_id' => 'id']);
    }

    public function getEvidencesByType($type)
    {
        $command = $this->getEvidences();
        return $command->where('file_type = :type', [':type' => $type])->all();
    }

    public function getComments() 
    {
        return $this->hasMany(OrderComments::className(), ['order_id' => 'id']);
    }

    public function getComplains() 
    {
        return $this->hasMany(OrderComplains::className(), ['order_id' => 'id']);
    }

    // public function getDiscounts() 
    // {
    //     return $this->hasMany(OrderFee::className(), ['order_id' => 'id'])->where(['type' => OrderFee::TYPE_DISCOUNT]);
    // }

    // public function getPromotions() 
    // {
    //     return $this->hasMany(OrderFee::className(), ['order_id' => 'id'])->where(['type' => OrderFee::TYPE_PROMOTION]);
    // }

    // public function getFees() 
    // {
    //     return $this->hasMany(OrderFee::className(), ['order_id' => 'id'])->where(['type' => OrderFee::TYPE_FEE]);
    // }

    // public function getTaxes() 
    // {
    //     return $this->hasMany(OrderFee::className(), ['order_id' => 'id'])->where(['type' => OrderFee::TYPE_TAX]);
    // }

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(10);
    }

    public function isVerifyingOrder()
    {
        return $this->status === self::STATUS_VERIFYING;
    }

    public function isPendingOrder()
    {
        return $this->status === self::STATUS_PENDING;
    }

    public function isProcessingOrder()
    {
        return $this->status === self::STATUS_PROCESSING;
    }

    public function isCompletedOrder()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isDeletedOrder()
    {
        return $this->status === self::STATUS_DELETED;
    }

    public function isRating()
    {
        return $this->rating;
    }

    public function getPercent()
    {
        if (!$this->total_unit) return 0;
        return floor(min(1, $this->doing_unit / $this->total_unit) * 100);
    }

    public function getProcessDurationTime()
    {
        if (!$this->process_start_time) return 0;
        if ($this->process_duration_time) return $this->process_duration_time;
        return strtotime('now') - strtotime($this->process_start_time);
    }

    public function getGamePack()
    {
        return ($this->quantity) ? $this->quantity : 1;
    }
    
    public function hasCancelRequest()
    {
        return $this->request_cancel && ($this->isPendingOrder() || $this->isVerifyingOrder());
    }

    public function tooLongProcess()
    {
        return ($this->getProcessDurationTime() > 3600) && $this->isPendingOrder();
    }

    public function ratingGood()
    {
        return $this->rating == 1;
    }

    public function delete()
    {
        if ($this->isVerifyingOrder()) return parent::delete();
        $this->status = self::STATUS_DELETED;
        return $this->save();
    }
}
