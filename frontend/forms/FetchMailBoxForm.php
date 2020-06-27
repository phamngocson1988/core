<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\MailThread;

class FetchMailBoxForm extends Model
{
    public $user_id;
    public $type; //inbox||sent

    private $_command;
    
    protected function createCommand()
    {
        $command = MailThread::find();
        switch ($this->type) {
            case 'inbox':
                $command->andWhere(['to' => $this->user_id]);
                break;
            case 'sent':
                $command->andWhere(['from' => $this->user_id]);
            default:
                $command->andWhere(['or',
                   ['from' => $this->user_id],
                   ['to' => $this->user_id],
               ]);
                break;
        }
        $command->orderBy(['updated_at' => SORT_DESC]);
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
