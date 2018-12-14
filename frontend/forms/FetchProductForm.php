<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Product;

class FetchProductForm extends Model
{
    public $category_id;

    private $_command;
    
    public function fetch()
    {
        $command = $this->getCommand();
        return $command->all();
    }

    protected function createCommand()
    {
        $command = Product::find();

        if ($this->category_id) {
            $postTable = Post::tableName();
            $categoryTable = PostCategory::tableName();
            $command->leftJoin($categoryTable, "$categoryTable.post_id = $postTable.id")
                    ->andWhere(["$categoryTable.category_id" => $this->category_id]);
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
