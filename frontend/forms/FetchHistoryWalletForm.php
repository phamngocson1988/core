<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use common\models\UserWallet as Wallet;

class FetchHistoryWalletForm extends Model
{
    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Wallet::find();

        $this->_command = $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
