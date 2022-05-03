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
use common\behaviors\OrderComplainBehavior;
use common\behaviors\OrderSupplierBehavior;
use common\behaviors\OrderMailBehavior;
use common\behaviors\OrderLogBehavior;
use common\components\wings\WingsBehavior;
/**
 * Order model
 */
class Order extends ActiveRecord
{
    const STATUS_VERIFYING = 'verifying';
    const STATUS_PENDING = 'pending';
    const STATUS_PROCESSING = 'processing';
    const STATUS_PARTIAL = 'partial';
    const STATUS_COMPLETED = 'completed';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_DELETED = 'deleted';
    const STATUS_CANCELLED = 'cancelled';

    const STATE_PENDING_INFORMATION = 'pending_information';
    const STATE_PENDING_CONFIRMATION = 'pending_confirmation';

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
            'complain' => OrderComplainBehavior::className(),
            // ['class' => OrderComplainBehavior::className()],
            ['class' => OrderLogBehavior::className()],
            ['class' => OrderSupplierBehavior::className()],
            ['class' => OrderMailBehavior::className()],
            'wings' => WingsBehavior::className(),
        ];
    }

    public function getId() 
    {
        return $this->id;
    }
    
    public static function getStatusList()
    {
        return [
            self::STATUS_VERIFYING => 'Verifying',
            self::STATUS_PENDING => 'Pending',
            self::STATUS_PROCESSING => 'Processing',
            self::STATUS_PARTIAL => 'Partial',
            self::STATUS_COMPLETED => 'Completed',
            self::STATUS_CONFIRMED => 'Confirmed',
            self::STATUS_DELETED => 'Deleted',
            self::STATUS_CANCELLED => 'Cancelled',
        ];
    }

    public static function getLoginMethodList()
    {
        return ['account' => 'Game account', 'facebook' => 'Facebook', 'google' => 'Google'];
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

    public function getCustomerName()
    {
        $customer = $this->customer;
        return $customer ? $customer->name : '';
    }

    public function getGame()
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getRecoverFile()
    {
        return $this->hasOne(File::className(), ['id' => 'recover_file_id']);
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

    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(10);
    }

    public function generatePaymentToken($data)
    {
        if (!$this->auth_key) return;
        $this->payment_token = \Firebase\JWT\JWT::encode($data, $this->auth_key, 'HS256');
    }

    public function validatePaymentToken($token) 
    {
        try {
            if (!$this->auth_key) return false;
            $decoded = \Firebase\JWT\JWT::decode($token, $this->auth_key, ['HS256']);
            return (array)$decoded;
        } catch (\Exception $e) {
            return false;
        }
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

    public function isPartialOrder()
    {
        return $this->status === self::STATUS_PARTIAL;
    }

    public function isCompletedOrder()
    {
        return $this->status === self::STATUS_COMPLETED;
    }

    public function isConfirmedOrder()
    {
        return $this->status === self::STATUS_CONFIRMED;
    }

    public function isDeletedOrder()
    {
        return $this->status === self::STATUS_DELETED;
    }

    public function isCancelledOrder()
    {
        return $this->status === self::STATUS_CANCELLED;
    }

    public function isRating()
    {
        return $this->rating;
    }

    public function getPercent()
    {
        if (!$this->original_quantity) return 0;
        return floor(min(1, $this->doing_unit / $this->original_quantity) * 100);
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
        $statusList = [
            Order::STATUS_VERIFYING,
            Order::STATUS_PENDING,
            Order::STATUS_PROCESSING,
            Order::STATUS_PARTIAL
        ];
        return $this->request_cancel && in_array($this->status, $statusList);
    }

    public function tooLongProcess()
    {
        return ($this->getProcessDurationTime() > 3600) && $this->isPendingOrder();
    }

    public function ratingGood()
    {
        return $this->rating == 1;
    }

    public function getNetProfit()
    {
        $managing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'managing_cost_rate', 0);
        $investing_cost_rate = Yii::$app->settings->get('ApplicationSettingForm', 'investing_cost_rate', 0);
        $total_price = $this->total_price;
        $total_cogs_price = $this->total_cogs_price;
        $quantity = $this->quantity;
        return $total_price - $total_cogs_price - ($managing_cost_rate + $investing_cost_rate) * $total_price / 100;
    }

    public function getReseller()
    {
        return $this->hasOne(OrderReseller::className(), ['order_id' => 'id']);
    }

    public function getPaymentData()
    {
        $content = $this->payment_content;
        if ($this->payment_type == 'online') {
            $data = json_decode($this->payment_data, true);
            if ($data && is_array($data)) {
                $params = [];
                foreach ($data as $key => $value) {
                    $newKey = sprintf("{%s}", $key);
                    if (strpos($content, $newKey) !== false) {
                        $params[$newKey] = $value;
                    }
                }
                $content = str_replace(array_keys($params), array_values($params), $content);
            }
        }
        return $content;
    }

    public function delete()
    {
        $this->status = self::STATUS_DELETED;
        return $this->save();
    }
}
