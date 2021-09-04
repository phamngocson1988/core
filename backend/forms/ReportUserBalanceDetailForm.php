<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet;
use common\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class ReportUserBalanceDetailForm extends Model
{
    public $start_date;
    public $end_date;
    public $user_id;
    public $type;
    public $ref_key;
    private $_user;

    protected $_command;
    protected $_page;

    public function createCommand()
    {
        $condition = [
            "status" => UserWallet::STATUS_COMPLETED,
            "user_id" => $this->user_id,
            "type" => $this->type,
            "ref_key" => $this->ref_key,
        ];
        $condition = array_filter($condition);
        $command = UserWallet::find()
        ->select(['id', 'description', 'type', 'updated_at', 'ref_name', 'ref_key', 'payment_at', 'coin'])
        ->asArray()
        ->indexBy("id")
        ->orderBy(["updated_at" => SORT_DESC])
        ->where($condition)
        ;
        if ($this->start_date) {
            $command->andWhere(['>=', "updated_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "updated_at", $this->end_date]);
        }
        return $command;
    }

    public function getPage()
    {
        if (!$this->_page) {
            $command = $this->getCommand();
            $pages = new Pagination(['totalCount' => $command->count()]);
            $this->_page = $pages;
        }
        return $this->_page;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }

    public function getFinalTotal($dateTime)
    {
        $command = UserWallet::find()
        ->where([
            "status" => UserWallet::STATUS_COMPLETED,
            "user_id" => $this->user_id
        ])
        ->andWhere(['<=', "updated_at", $dateTime]);
        return $command->sum('coin');
    }

    public function getReport()
    {
        $command = $this->getCommand();
        $pages = $this->getPage();
        $data = $command->offset($pages->offset)->limit($pages->limit)->all();
       
        if (!count($data)) return [];
        $firstRow = reset($data);
        $dateTime = $firstRow['updated_at'];
        $balanceEnd = $this->getFinalTotal($dateTime);
        foreach ($data as $key => &$row) {
            $row['balance_end'] = $balanceEnd;
            $row['balance_start'] = $balanceEnd - (float)$row['coin'];
            $balanceEnd = $row['balance_start'];
        }
        return $data;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function fetchType()
    {
        return [
            UserWallet::TYPE_INPUT => "Nạp vào",
            UserWallet::TYPE_OUTPUT => "Rút ra"
        ];
    }
}
