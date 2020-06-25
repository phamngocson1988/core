<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\MailThread;

class FetchMailBoxForm extends Model
{
    public $user_id;

    private $_command;
    
    protected function createCommand()
    {
        $command = MailThread::find();
        $command->andWhere(['created_by' => $this->user_id]);
        $command->orderBy(['created_at' => SORT_DESC]);
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
