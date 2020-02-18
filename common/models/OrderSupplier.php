<?php
namespace common\models;

use Yii;
use yii\db\ActiveRecord;
use yii\behaviors\TimestampBehavior;

/**
 * OrderComments model
 */
class OrderSupplier extends ActiveRecord
{
	const STATUS_REQUEST = 'request';
    const STATUS_APPROVE = 'approve';
    const STATUS_PROCESSING = 'processing';
    const STATUS_COMPLETED = 'completed';
	const STATUS_PARTIAL = 'partial';
	const STATUS_REJECT = 'reject';
	const STATUS_RETAKE = 'retake';
	const STATUS_STOP = 'stop';

    public static function tableName()
    {
        return '{{%order_supplier}}';
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

    public function getOrder() 
    {
        return $this->hasOne(Order::className(), ['id' => 'order_id']);
    }

    public function getGame() 
    {
        return $this->hasOne(Game::className(), ['id' => 'game_id']);
    }

    public function getGameTitle() 
    {
        $game = $this->game;
        return $game ? $game->title : '';
    }

    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'supplier_id']);
    }

    public function isRequest()
    {
        return $this->status == self::STATUS_REQUEST;
    }

    public function isApprove()
    {
        return $this->status == self::STATUS_APPROVE;
    }

    public function isProcessing()
    {
        return $this->status == self::STATUS_PROCESSING;
    }

    public function isCompleted()
    {
        return $this->status == self::STATUS_COMPLETED;
    }

    public function canBeTaken()
    {
        $requestStatus = [
            self::STATUS_REQUEST, 
            self::STATUS_APPROVE, 
            self::STATUS_PROCESSING,
            self::STATUS_PARTIAL,
        ];
        return in_array($this->status, $requestStatus);
    }

    public function canBeRejected()
    {
        $requestStatus = [
            self::STATUS_REQUEST, 
            self::STATUS_APPROVE, 
        ];
        return in_array($this->status, $requestStatus);
    }

    public function getPercent()
    {
        if (!$this->quantity) return 0;
        return round($this->doing * 100 / $this->quantity);
    }
}
