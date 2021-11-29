<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use website\models\Post;
use common\models\Category;
use common\models\PostCategory;

class FetchPostForm extends Model
{
    public $hot;
    public $category_id;
    
    private $_command;
    private $_category;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Post::find();
        $postTable = Post::tableName();

        if ($this->category_id) {
            $categoryTable = PostCategory::tableName();
            $command->innerJoin($categoryTable, "$postTable.id = $categoryTable.post_id");
            $command->andWhere(["$categoryTable.category_id" => $this->category_id]);
        }

        if ($this->hot) {
            $command->andWhere(["$postTable.hot" => 1]);
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

    public function getCategory()
    {
        if (!$this->_category) {
            $this->_category = Category::findOne($this->category_id);
        }
        return $this->_category;
    }
}
