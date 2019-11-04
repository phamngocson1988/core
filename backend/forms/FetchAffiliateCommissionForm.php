<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;
use backend\models\UserAffiliate;
use backend\models\UserCommission;
use backend\models\UserCommissionWithdraw;
use backend\models\Order;

class FetchAffiliateCommissionForm extends Model
{
    public $id;
    public $user_id;
    public $member_id;
    public $report_start_date;
    public $report_end_date;
    public $status;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        // $duration = Yii::$app->settings->get('AffiliateProgramForm', 'duration', 30);
        $today = date('Y-m-d H:i:s');
        $command = UserCommission::find();
        if ($this->id) {
            $command->andWhere(["id" => $this->id]);
        }

        if ($this->user_id) {
            $command->andWhere(["user_id" => $this->user_id]);
        }

        if ($this->member_id) {
            $command->andWhere(["member_id" => $this->member_id]);
        }

        if ($this->report_start_date) {
            $command->andWhere(['>=', "created_at", $this->report_start_date . " 00:00:00"]);
        }
        if ($this->report_end_date) {
            $command->andWhere(['<=', "created_at", $this->report_end_date . " 23:59:59"]);
        }

        switch ($this->status) {
            case 'pending':
                $command->andWhere(['>', "valid_from_date", $today]);
                $command->andWhere(["status" => UserCommission::STATUS_VALID]);
                break;
            case 'ready':
                $command->andWhere(['<=', "valid_from_date", $today]);
                $command->andWhere(["status" => UserCommission::STATUS_VALID]);
                break;
            case 'withdrawed':
                $command->andWhere(["status" => UserCommission::STATUS_WITHDRAWED]);
                break;
            default:
                $command->andWhere(["status" => UserCommission::STATUS_VALID]);
                break;
            
        }

        $orderBy = ["created_at" => SORT_DESC];
        $command->orderBy($orderBy);
        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }

    public function getMember()
    {
        if ($this->member_id) return User::findOne($this->member_id);
        return null;
    }

    public function getStatusList()
    {
        return [
            'pending' => 'Pending',
            'ready' => 'Ready',
            'withdrawed' => 'Withdrawed'
        ];
    }
}
