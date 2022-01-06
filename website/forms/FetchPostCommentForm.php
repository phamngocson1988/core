<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use common\models\PostComment;

class FetchPostCommentForm extends Model
{
    public $post_id;
    public $sort = 'desc';
    public $limit = 10;
    public $lastKey;
    
    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = PostComment::find()->where(['post_id' => $this->post_id])->andWhere(['is', 'parent_id', new \yii\db\Expression('null')]);
        $orderBy = 'ASC' === strtoupper($this->sort) ? SORT_ASC : SORT_DESC;
        $operatorCompare = 'ASC' === strtoupper($this->sort) ? '>' : '<';
        if ($this->lastKey) {
            $command->andWhere([$operatorCompare, 'id', $this->lastKey]);
        }
        $command->orderBy(['id' => $orderBy]);
        $command->limit($this->limit);
        $this->_command = $command;
    }

    public function getTotal() 
    {
        return PostComment::find()->where(['post_id' => $this->post_id])->andWhere(['is', 'parent_id', new \yii\db\Expression('null')])->count();
    }

    public function getCommand()
    {
        if (!$this->_command) {
            $this->createCommand();
        }
        return $this->_command;
    }
}
