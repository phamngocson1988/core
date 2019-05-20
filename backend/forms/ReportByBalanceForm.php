<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet;
use common\models\User;

class ReportByBalanceForm extends Model
{
    public $start_date;
    public $end_date;
    public $user_id;
    private $_user;
    private $_command;

    public function rules()
    {
        return [
            ['start_date', 'default', 'value' => date('Y-m-01')],
            ['end_date', 'default', 'value' => date('Y-m-t')],
            ['user_id', 'trim']
        ];
    }

    public function fetch()
    {
        // Find all users in period
        $command = $this->getUserCommand();
        $models = $command->all();//print_r($models);echo $command->createCommand()->getRawSql();die;
        $users = [];
        foreach ($models as $model) {
            $userId = $model->user_id;

            // find topup/ withdraw
            $topupCommand = $this->getCommand();
            $topupCommand->andWhere(['type' => UserWallet::TYPE_INPUT]);
            $topupCommand->andWhere(['user_id' => $userId]);
            $totalTopup = $topupCommand->sum('coin');

            $withdrawCommand = $this->getCommand();
            $withdrawCommand->andWhere(['type' => UserWallet::TYPE_OUTPUT]);
            $withdrawCommand->andWhere(['user_id' => $userId]);
            $totalWithdraw = $withdrawCommand->sum('coin');

            // find balance
            $balanceAtStart = UserWallet::find();
            $balanceAtStart->orderBy(['payment_at' => SORT_DESC]);
            $balanceAtStart->where(['user_id' => $userId]);
            $balanceAtStart->andWhere(['<=', 'payment_at', $this->start_date . " 23:59:59"]);
            $balanceAtStartModel = $balanceAtStart->one();
            $balanceAtStartNumber = ($balanceAtStartModel) ? $balanceAtStartModel->balance : 0;

            $balanceAtEnd = UserWallet::find();
            $balanceAtEnd->orderBy(['payment_at' => SORT_DESC]);
            $balanceAtEnd->where(['user_id' => $userId]);
            $balanceAtEnd->andWhere(['<=', 'payment_at', $this->end_date . " 23:59:59"]);
            $balanceAtEndModel = $balanceAtEnd->one();
            $balanceAtEndNumber = ($balanceAtEndModel) ? $balanceAtEndModel->balance : 0;

            $users[$model->user_id]['name'] = $model->user->name;
            $users[$model->user_id]['topup'] = $totalTopup;
            $users[$model->user_id]['withdraw'] = $totalWithdraw;
            $users[$model->user_id]['balance_start'] = $balanceAtStartNumber;
            $users[$model->user_id]['balance_end'] = $balanceAtEndNumber;
        }
        return $users;
    }

    protected function createCommand()
    {
        $command = UserWallet::find();
        $command->where(["status" => UserWallet::STATUS_COMPLETED]);

        if ($this->start_date) {
            $command->andWhere(['>=', 'payment_at', $this->start_date . " 00:00:00"]);
        }
        if ($this->end_date) {
            $command->andWhere(['<=', 'payment_at', $this->end_date . " 23:59:59"]);
        }

        if ($this->user_id) {
            $command->andWhere(['user_id' => $this->user_id]);
        }

        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return clone $this->_command;
    }

    public function getUserCommand()
    {
        $command = $this->getCommand();
        $command->select(['id', 'user_id']);
        $command->with('user');
        $command->groupBy('user_id');
        return $command;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

}
