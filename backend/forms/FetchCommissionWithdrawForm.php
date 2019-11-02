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

class FetchCommissionWithdrawForm extends Model
{
    public $user_id;
    public $status;
    public $created_start_date;
    public $created_end_date;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = UserCommissionWithdraw::find();
        if ($this->user_id) {
            $command->andWhere(["user_id" => $this->user_id]);
        }

        if ($this->status) {
            $command->andWhere(["status" => $this->status]);
        }

        if ($this->created_start_date) {
            $command->andWhere(['>=', "created_at", $this->created_start_date . " 00:00:00"]);
        }
        if ($this->created_end_date) {
            $command->andWhere(['<=', "created_at", $this->created_end_date . " 23:59:59"]);
        }

        $command->with('user');
        $orderBy = ["id" => SORT_DESC];
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

    public function getUser()
    {
        if ($this->user_id) return User::findOne($this->user_id);
        return null;
    }
}
