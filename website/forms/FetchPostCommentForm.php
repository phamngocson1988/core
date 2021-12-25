<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use common\models\PostComment;

class FetchPostCommentForm extends Model
{
    public $post_id;
    
    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PostComment::find()->where(['post_id' => $this->post_id])->andWhere(['is', 'parent_id', new \yii\db\Expression('null')]);
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
