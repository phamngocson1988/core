<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\Post;
use frontend\models\PostCategory;

class FetchPostForm extends Model
{
    public $category_id;

    private $_command;
    
    protected function createCommand()
    {
        $command = Post::find();
        $postTable = Post::tableName();
        $categoryTable = PostCategory::tableName();
        $command->innerJoin($categoryTable, "{$postTable}.id = {$categoryTable}.post_id");
        $command->select(["{$postTable}.*"]);
        if ($this->category_id) {
            $command->where(["{$categoryTable}.category_id" => $this->category_id]);
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
