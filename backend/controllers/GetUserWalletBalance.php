<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet;

class GetUserWalletBalance extends UserWallet
{
    public $date;

    public function rules()
    {
        return [
            ['date', 'required'],
            ['user_id', 'required']
        ];
    }

    private $_command;
    
    protected function createCommand()
    {
        $command = self::find();
        $command->where(["status" => self::STATUS_COMPLETED]);

        if ($this->date) {
            $command->andWhere(['<=', 'payment_at', $this->date . " 23:59:59"]);
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
        return $this->_command;
    }

    public function count()
    {
        $command = $this->getCommand()->sum('coin');

    }

}
