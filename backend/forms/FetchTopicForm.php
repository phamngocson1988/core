<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\ForumTopic;
use backend\models\ForumPost;
use backend\models\ForumCategory;

class FetchTopicForm extends Model
{
    public $q;
    public $category_id;
    public $user_id;

    private $_command;
    
    public function attributeLabels()
    {
        return [
            'q' => Yii::t('app', 'keyword'),
            'category_id' => Yii::t('app', 'category'),
            'user_id' => Yii::t('app', 'user'),
        ];
    }
    protected function createCommand()
    {
        $command = ForumTopic::find();
        $postTable = ForumPost::tableName();
        $topicTable = ForumTopic::tableName();
        $command->innerJoin($postTable, "{$postTable}.topic_id = {$topicTable}.id");
        $command->select(["distinct {$topicTable}.*"]);
        if ($this->category_id) {
            $command->where(["{$topicTable}.category_id" => $this->category_id]);
        }
        if ($this->q) {
            $command->andWhere(['or',
                ['like', "{$topicTable}.subject", $this->q],
                ['like', "{$postTable}.content", $this->q],

            ]);
        }
        if ($this->user_id) {
            $command->andWhere(["{$postTable}.created_by" => $this->user_id]);
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
        $categories = ForumCategory::find()->select(['id', 'title'])->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }
}
