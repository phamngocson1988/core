<?php
namespace backend\models;

use yii\helpers\ArrayHelper;

class OrderSupplier extends \common\models\OrderSupplier
{
	public static function getStatusList()
    {
        return [
            self::STATUS_REQUEST => 'Yêu cầu',
			self::STATUS_APPROVE => 'Đã nhận',
			self::STATUS_PROCESSING => 'Đang thực hiện',
			self::STATUS_COMPLETED => 'Đã hoàn thành',
			self::STATUS_PARTIAL => 'Đã hoàn thành môt phần',
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
