<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Post;
use backend\models\Category;
use backend\models\PostCategory;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class EditPostForm extends Model
{
    public $id;
    public $title;
    public $content;
    public $image_id;
    public $category_ids;
    public $operator_id;
    public $status;

    protected $_post;

    public function rules()
    {
        return [
            [['id', 'title', 'content', 'status'], 'required'],
            ['id', 'validatePost'],
            [['image_id', 'category_ids', 'operator_id'], 'safe'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'title' => Yii::t('app', 'title'),
            'content' => Yii::t('app', 'content'),
            'category_ids' => Yii::t('app', 'category'),
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
        $post->operator_id = $this->operator_id;
        $post->status = $this->status;
        $result = $post->save();

        if ($result) {
            $cats = PostCategory::find()->where(['post_id' => $post->id])->all();
            foreach ($cats as $cat) {
                $cat->delete();
            }
            $categoryIds = array_filter((array)$this->category_ids);
            foreach ($categoryIds as $categoryId) {
                $postCat = new PostCategory();
                $postCat->category_id = $categoryId;
                $postCat->post_id = $post->id;
                $postCat->save();
            }
        }
        return $result;
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
        $this->operator_id = $post->operator_id;
        $this->status = $post->status;

        // Categories
        $categories = (array)$post->categories;
        $this->category_ids = ArrayHelper::getColumn($categories, 'id');
    }

    public function getPost()
    {
        if (!$this->_post) {
            $this->_post = Post::findOne($this->id);
        }
        return $this->_post;
    }
}
