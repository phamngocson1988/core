<?php
namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\GameGroup;

class FetchGameGroupForm extends Model
{
    public $q;

    private $_command;
    
    protected function createCommand()
    {
        $q = $this->q;
        $command = GameGroup::find();
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
