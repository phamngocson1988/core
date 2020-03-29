<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Bank;

class FetchBankForm extends Model
{
    public $name;
    public $country;
    public $code;

    private $_command;

    protected function createCommand()
    {
        $command = Bank::find();
        if ($this->name) {
            $command->andWhere(['like', 'name', $this->name]);
        }
        if ($this->country) {
            $command->andWhere(['country' => $this->country]);
        }
        if ($this->code) {
            $command->andWhere(['like', 'code', $this->code]);
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
}
