<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Post;
use backend\models\Category;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class CreatePostForm extends Model
{
    public $title;
    public $content;
    public $image_id;
    public $category_id;
    public $operator_id;
    public $status;

    public function rules()
    {
        return [
            [['title', 'content', 'status'], 'required'],
            [['image_id', 'category_id', 'operator_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'content'),
            'category_id' => Yii::t('app', 'category'),
            'operator' => Yii::t('app', 'operator'),
            'status' => Yii::t('app', 'status'),
        ];
    }
    
    public function create()
    {
        $post = new Post();
        $post->title = $this->title;
        $post->content = $this->content;
        $post->image_id = $this->image_id;
        $post->category_id = $this->category_id;
        $post->operator_id = $this->operator_id;
        $post->status = $this->status;
        return $post->save();
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
