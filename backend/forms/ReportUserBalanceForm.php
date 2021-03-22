<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet;
use common\models\User;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;

class ReportUserBalanceForm extends Model
{
    public $start_date;
    public $end_date;
    public $user_id;
    public $type;
    private $_user;

    protected $_command;
    protected $_page;

    public function createCommand()
    {
        $userWalletTable = UserWallet::tableName();

        $command = UserWallet::find()
        ->select([
            "$userWalletTable.user_id AS id", 
            "IFNULL(SUM( $userWalletTable.coin ), 0) AS balance_end",
        ])
        ->groupBy(["$userWalletTable.user_id"])
        ->orderBy(["balance_end" => SORT_DESC])
        ->asArray()
        ->indexBy("id")
        ->where(["$userWalletTable.status" => UserWallet::STATUS_COMPLETED])
        ;
        if ($this->user_id) {
            $command->andWhere(["$userWalletTable.user_id" => $this->user_id]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$userWalletTable.updated_at", $this->end_date]);
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

    public function getUserInformation($userIds) 
    {
        return User::find()->where(['id' => $userIds])->select(["id", "name"])->indexBy("id")->asArray()->all();
    }

    public function getPeriodTotal($userIds) 
    {
        $userWalletTable = UserWallet::tableName();

        $command = UserWallet::find()
        ->select([
            "$userWalletTable.user_id AS id", 
            "SUM( IF ( $userWalletTable.type = 'I', $userWalletTable.coin, 0 ) ) AS topup",
            "SUM( IF ( $userWalletTable.type = 'O', $userWalletTable.coin, 0 ) ) AS withdraw",
        ])
        ->groupBy(["$userWalletTable.user_id"])
        ->asArray()
        ->indexBy("id")
        ->where([
            "$userWalletTable.status" => UserWallet::STATUS_COMPLETED,
            "$userWalletTable.user_id" => $userIds,
        ]);
        if ($this->start_date) {
            $command->andWhere(['>=', "$userWalletTable.updated_at", $this->start_date]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', "$userWalletTable.updated_at", $this->end_date]);
        }
        return $command->all();
    }

    public function getBeginningTotal($userIds)
    {
        $defaultData = [];
        foreach ($userIds as $userId) {
            $defaultData[$userId] = ['balance_start' => 0];
        }
        if (!$this->start_date) {
            return $defaultData;
        }

        $userWalletTable = UserWallet::tableName();
        $command = UserWallet::find()
        ->select([
            "$userWalletTable.user_id AS id", 
            "IFNULL(SUM( $userWalletTable.coin ), 0) AS balance_start",
        ])
        ->groupBy(["$userWalletTable.user_id"])
        ->asArray()
        ->indexBy("id")
        ->where([
            "$userWalletTable.status" => UserWallet::STATUS_COMPLETED,
            "$userWalletTable.user_id" => $userIds
        ])
        ;
        if ($this->start_date) {
            $command->andWhere(['<', "$userWalletTable.updated_at", $this->start_date]);
        }
        return $command->all();
    }

    public function getReport()
    {
        $command = $this->getCommand();
        $pages = $this->getPage();
        $endingTotal = $command->offset($pages->offset)->limit($pages->limit)->all();
        $userIds = array_keys($endingTotal);

        $userInfor = $this->getUserInformation($userIds);
        $periodTotal = $this->getPeriodTotal($userIds);
        $beginningTotal = $this->getBeginningTotal($userIds);
       
        $data = [];
        $template = [
            'name' => '',
            'topup' => 0,
            'withdraw' => 0,
            'balance_start' => 0,
            'balance_end' => 0,
        ];
        foreach ($endingTotal as $key => $ending) {
            $user = ArrayHelper::getValue($userInfor, $key, []);
            $period = ArrayHelper::getValue($periodTotal, $key, []);
            $beginning = ArrayHelper::getValue($beginningTotal, $key, []);
            $data[$key] = array_merge($template, $ending, $user, $period, $beginning);
        }
        // echo '<pre>';
        // print_r($periodTotal);die;
        return $data;
        // income/outcome
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }
}
