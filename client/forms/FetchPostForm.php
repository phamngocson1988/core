<?php

namespace client\forms;

use Yii;
use yii\base\Model;
use common\models\Post;

class FetchPostForm extends Model
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
        $command = Post::find();

        if ($this->q) {
            $command->orWhere(['like', 'title', $this->q]);
            $command->orWhere(['like', 'excerpt', $this->q]);
            $command->orWhere(['like', 'content', $this->q]);
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
