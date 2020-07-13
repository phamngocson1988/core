<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\FlashSale;

class FetchFlashSaleForm extends Model
{
    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = FlashSale::find()->orderBy(['id' => SORT_DESC]);
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
