<?php
namespace backend\forms;

use yii\base\Model;
use backend\models\SupplierWithdrawRequest;
use Yii;

class FetchSupplierWithdrawRequestForm extends Model
{
    public $status;
    public $done_from;
    public $done_to;

    private $_command;

    protected function createCommand()
    {
        $this->status = (array)$this->status;
        if (!$this->status) {
            $this->status = $this->getDefaultStatusList();
        }
        $command = SupplierWithdrawRequest::find()->where(["IN", 'status', $this->status]);

        if ($this->done_from) {
            $command->andWhere(['>=', 'done_at', sprintf("%s 00:00:00", $this->done_from)]);
        }

        if ($this->done_to) {
            $command->andWhere(['<=', 'done_at', sprintf("%s 23:59:59", $this->done_to)]);
        }
        // die($command->createCommand()->getRawSql());
        return $command;
    }


    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function getStatusList()
    {
        return [
            SupplierWithdrawRequest::STATUS_REQUEST => "Gửi yêu cầu",
            SupplierWithdrawRequest::STATUS_APPROVE => "Đã phê duyệt",
            SupplierWithdrawRequest::STATUS_DONE => "Đã hoàn tất",
            SupplierWithdrawRequest::STATUS_CANCEL => "Hủy bỏ",
        ];
    }

    public function getDefaultStatusList()
    {
        return [
            SupplierWithdrawRequest::STATUS_REQUEST,
            SupplierWithdrawRequest::STATUS_APPROVE
        ];
    }

}