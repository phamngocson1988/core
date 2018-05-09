<?php

namespace client\forms;

use Yii;
use yii\base\Model;
use common\models\Subscriber;

class FetchSubscriberForm extends Model
{
    public $q;

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Subscriber::find();

        if ($this->q) {
            $command->where(['like', 'email', $this->q]);
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
