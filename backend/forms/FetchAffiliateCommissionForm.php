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
    public $user_id;
    public $member_id;
    public $report_start_date;
    public $report_end_date;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = UserCommission::find();
        if ($this->user_id) {
            $command->andWhere(["user_id" => $this->user_id]);
        }

        if ($this->member_id) {
            $command->andWhere(["member_id" => $this->member_id]);
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
}
