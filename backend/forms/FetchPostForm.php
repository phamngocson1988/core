<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Post;
use backend\models\Category;
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

        if ($this->q) {
            $command->andWhere(['or',
                ['like', 'title', $this->q],
                ['like', 'content', $this->q],

            ]);
        }
        if ($this->category_id) {
            $command->andWhere(['category_id' => $this->category_id]);
        }
        if ($this->operator_id) {
            $command->andWhere(['operator_id' => $this->operator_id]);
        }
        if ($this->status) {
            $command->andWhere(['status' => $this->status]);
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
