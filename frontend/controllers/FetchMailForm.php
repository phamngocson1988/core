<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Mail;

class FetchMailForm extends Model
{
    public $user_id;
    public $thread_id;

    private $_command;
    
    protected function createCommand()
    {
        $command = Mail::find();
        $command->andWhere(['from' => $this->user_id]);
        $command->andWhere(['mail_thread_id' => $this->thread_id]);
        $command->orderBy(['created_at' => SORT_ASC]);
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
