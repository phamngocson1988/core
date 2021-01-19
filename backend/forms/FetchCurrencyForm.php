<?php
namespace backend\forms;

use yii\base\Model;
use common\models\CurrencySetting;
use Yii;

class FetchCurrencyForm extends Model
{
    public $status;

    private $_command;

    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = CurrencySetting::find();
        if ($this->status) {
            $command->where(['status' => $this->status]);
        }
        return $command;
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->_command = $this->createCommand();
        }
        return $this->_command;
    }
}