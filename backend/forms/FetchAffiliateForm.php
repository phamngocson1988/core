<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\User;
use backend\models\Affiliate;
use backend\models\Order;

class FetchAffiliateForm extends Model
{
    public $user_id;
    public $status;
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
        $affiliateTable = Affiliate::tableName();
        $command = Affiliate::find();
        if ($this->user_id) {
            $command->andWhere(["{$affiliateTable}.user_id" => $this->user_id]);
        }

        if ($this->status) {
            $command->andWhere(["{$affiliateTable}.status" => $this->status]);
        }

        $command->with('user');
        $orderBy = ["{$affiliateTable}.created_at" => SORT_DESC];
        $command->orderBy($orderBy);
        // echo $command->createCommand()->getRawSql();die;
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
