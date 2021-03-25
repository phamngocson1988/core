<?php
namespace api\forms;

use Yii;
use yii\base\Model;
use api\models\User;

class FetchUserForm extends Model
{
    public $q;

    private $_command;
    
    protected function createCommand()
    {
        $q = trim($this->q);
        $command = User::find();
        if ($q) {
            $command->orWhere(['like', 'id', $q]);
            $command->orWhere(['like', 'username', $q]);
            $command->orWhere(['like', 'name', $q]);
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
