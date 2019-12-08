<?php
namespace supplier\forms;

use Yii;
use yii\base\Model;
use supplier\models\Game;

class FetchGameForm extends Model
{
    public $q;
    public $status;

    private $_command;
    
    protected function createCommand()
    {
        $q = $this->q;
        $status = $this->status;
        $command = Game::find();
        $command->where(['<>', 'status', Game::STATUS_DELETE]);
        if ($status) {
            $command->andWhere(['status' => $status]);
        }
        if ($q) {
            $command->andWhere(['like', 'title', $q]);
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
