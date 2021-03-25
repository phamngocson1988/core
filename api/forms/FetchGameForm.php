<?php
namespace api\forms;

use Yii;
use yii\base\Model;
use api\models\Game;

class FetchGameForm extends Model
{
    public $q;

    private $_command;
    
    protected function createCommand()
    {
        $q = $this->q;
        $command = Game::find();
        if ($q) {
            $command->where(["like", "title", $q]);
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
