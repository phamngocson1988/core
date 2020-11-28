<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use backend\models\Post;
use backend\models\Category;
use backend\models\PostCategory;
use backend\models\Operator;
use yii\helpers\ArrayHelper;

class CreatePostForm extends Model
{
    public $title;
    public $content;
    public $image_id;
    public $category_ids;
    public $operator_id;
    public $language;
    public $status;

    public function init()
    {
        $languages = array_keys(Yii::$app->params['languages']);
        if (!in_array($this->language, $languages)) {
            $this->language = reset($languages);
        }
    }

    public function rules()
    {
        return [
            [['title', 'content', 'status'], 'required'],
            [['image_id', 'category_ids', 'operator_id'], 'safe'],
            ['language', 'required'],
            ['language', 'in', 'range' => array_keys(Yii::$app->params['languages'])],
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
            'language' => Yii::t('app', 'language'),
        ];
    }
    
    public function create()
    {
        $post = new Post();
        $post->title = $this->title;
        $post->content = $this->content;
        $post->image_id = $this->image_id;
        $post->operator_id = $this->operator_id;
        $post->status = $this->status;
        $post->language = $this->language;
        $result = $post->save();

        if ($result && $this->category_ids) {
            foreach ((array)$this->category_ids as $categoryId) {
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
        $categories = Category::find()
        ->select(['id', 'title'])->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }

    public function fetchOperator()
    {
        $operators = Operator::find()
        ->where(['language' => $this->language])
        ->select(['id', 'name'])->all();
        return ArrayHelper::map($operators, 'id', 'name');
    }

    public function fetchStatus()
    {
        return Post::getStatusList();
    }

    public function fetchLanguages()
    {
        return ArrayHelper::map(Yii::$app->params['languages'], 'code', 'title');
    }
}
