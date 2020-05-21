<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Post;
use backend\models\Category;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class EditPostForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $image_id;
    public $category_id;
    public $operator_id;
    public $status;

    protected $_post;

    public function rules()
    {
        return [
            [['id', 'title', 'content', 'status'], 'required'],
            ['id', 'validatePost'],
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

    public function validatePost($attribute, $params = []) 
    {
        $post = $this->getPost();
        if (!$post) {
            $this->addError($attribute, Yii::t('app', 'post_is_not_exist'));
        }
    }
    public function update()
    {
        $post = $this->getPost();
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

    public function loadData()
    {
        $post = $this->getPost();
        $this->title = $post->title;
        $this->content = $post->content;
        $this->image_id = $post->image_id;
        $this->category_id = $post->category_id;
        $this->operator_id = $post->operator_id;
        $this->status = $post->status;
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = Post::findOne($this->id);
        }
        return $this->_post;
    }
}
