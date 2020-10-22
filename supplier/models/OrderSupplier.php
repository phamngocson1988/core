<?php
namespace supplier\models;

use yii\helpers\ArrayHelper;

class OrderSupplier extends \common\models\OrderSupplier
{
	// public $distributed_time;
    
    // public $pending_time;
    public $approved_time;
    // public $waiting_time;
    public $login_time;
    public $processing_time;
    public $completed_time;
    public $confirmed_time;
    // public $supplier_completed_time;

	public static function getStatusList()
    {
        return [
            self::STATUS_REQUEST => 'Yêu cầu',
			self::STATUS_APPROVE => 'Đã nhận',
			self::STATUS_PROCESSING => 'Đang thực hiện',
			self::STATUS_COMPLETED => 'Đã hoàn thành',
			self::STATUS_CONFIRMED => 'Đã xác nhận',
			self::STATUS_REJECT => 'Đã từ chối',
			self::STATUS_RETAKE => 'Đã lấy lại',
			self::STATUS_STOP => 'Đã dừng',
        ];
    }

	public function getStatusLabel()
	{
		$list = self::getStatusList();
		return ArrayHelper::getValue($list, $this->status, '');
	}
}
