<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Post;
use backend\models\Category;
use backend\models\PostCategory;
use backend\models\Operator;

class FetchPostForm extends Model
{
    public $q;
    public $category_id;
    public $operator_id;
    public $status;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'category_id' => Yii::t('app', 'category'),
            'operator' => Yii::t('app', 'operator'),
            'status' => Yii::t('app', 'status'),
        ];
    }
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
        if ($this->q) {
            $command->andWhere(['or',
                ['like', "{$postTable}.title", $this->q],
                ['like', "{$postTable}.content", $this->q],

            ]);
        }
        if ($this->operator_id) {
            $command->andWhere(["{$postTable}.operator_id" => $this->operator_id]);
        }
        if ($this->status) {
            $command->andWhere(["{$postTable}.status" => $this->status]);
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

    public function fetchCategory()
    {
        $categories = Category::find()->select(['id', 'title'])->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }

    public function fetchOperator()
    {
        $operators = Operator::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($operators, 'id', 'name');
    }

    public function fetchStatus()
    {
        return Post::getStatusList();
    }
}
