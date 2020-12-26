<?php
namespace backend\models;

use Yii;
use backend\behaviors\OrderComplainBehavior;
use backend\behaviors\OrderNotificationBehavior;
use backend\models\PaymentTransaction;
/**
 * Order model
 */
class Order extends \common\models\Order
{
    public $distributed_time;
    
    public $pending_time;
    public $approved_time;
    public $waiting_time;
    public $login_time;
    public $processing_time;
    public $completed_time;
    public $confirmed_time;
    public $supplier_completed_time;
    public $supplier_pending_time;




    public $processing_waiting_time;
    public $completed_waiting_time;
    const SCENARIO_CREATE = 'create';
    const SCENARIO_GO_PENDING = 'go_pending';
    const SCENARIO_GO_PROCESSING = 'go_processing';
    const SCENARIO_GO_COMPLETED = 'go_completed';

    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['complain'] = OrderComplainBehavior::className();
        $behaviors['notification'] = OrderNotificationBehavior::className();
        return $behaviors;
    }

    public function scenarios()
    {
        $parents = parent::scenarios();
        return array_merge($parents, [
            self::SCENARIO_CREATE => ['game_id', 'customer_id', 'quantity', 'username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_VERIFYING => ['game_id', 'customer_id', 'total_unit', 'username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_PENDING => ['username', 'password', 'platform', 'login_method', 'character_name', 'recover_code', 'server', 'note'],
            self::SCENARIO_GO_PENDING => ['payment_method', 'payment_id'],
            self::SCENARIO_GO_PROCESSING => [],
            self::SCENARIO_GO_COMPLETED => [],
        ]);
    }

    public function rules()
    {
        return [
            ['payment_id', 'trim'],
            [['game_id', 'customer_id', 'quantity'], 'required', 'on' => self::SCENARIO_CREATE],
            [['game_id', 'customer_id', 'total_unit'], 'required', 'on' => self::SCENARIO_VERIFYING],
            [['username', 'password', 'platform', 'login_method', 'character_name'], 'required', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_VERIFYING]],
            [['recover_code', 'server', 'note'], 'trim', 'on' => [self::SCENARIO_CREATE, self::SCENARIO_VERIFYING]],
            [['payment_method', 'payment_id'], 'required', 'on' => self::SCENARIO_GO_PENDING],
            ['payment_id', 'validatePayment', 'on' => self::SCENARIO_GO_PENDING],
        ];
    }

    public function getStatusLabel($format = '<span class="label label-%s">%s</span>')
    {
        $list = [
            self::STATUS_VERIFYING => 'default',
            self::STATUS_PENDING => 'info',
            self::STATUS_PROCESSING => 'primary',
            self::STATUS_PARTIAL => 'primary',
            self::STATUS_COMPLETED => 'success',
            self::STATUS_DELETED => 'default',
            self::STATUS_CANCELLED => 'default',
            self::STATUS_CONFIRMED => 'default',
        ];
        $labels = self::getStatusList();
        $color = $list[$this->status];
        $label = $labels[$this->status];
        if (!$format) return $label;
        return sprintf($format, $color, $label);
    }

    public function validatePayment($attribute, $params = []) 
    {
        $payment = PaymentTransaction::find()
        ->where(['payment_id' => $this->payment_id])
        ->andWhere(['<>', 'status', PaymentTransaction::STATUS_DELETED])
        ->one();
        if ($payment) {
            return $this->addError($attribute, sprintf('SỐ LỆNH GIAO DỊCH đã được sử dụng cho giao dich %s', $payment->getId()));
        }
        $order = self::find()
        ->where(['payment_id' => $this->payment_id])
        ->andWhere(['<>', 'status', self::STATUS_DELETED])
        ->one();
        if (!$order) return true;
        if ($order->id != $this->id) {
            return $this->addError($attribute, 'SỐ LỆNH GIAO DỊCH đã được sử dụng');
        }
    }
}