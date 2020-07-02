<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Operator;

class FetchOperatorForm extends Model
{
    private $_command;
    
    protected function createCommand()
    {
        $command = Operator::find()->orderBy(['id' => SORT_DESC]);
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
