<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Category;
use frontend\models\Post;

class NewsByCategoryWidget extends Widget
{
	public $categoryId;
	public $class;

    public function run()
    {
        $posts = Post::find()->where(['category_id' => $this->categoryId])->limit(5)->all();
        $category = Category::findOne($this->categoryId);
        return $this->render('news-by-category', [
            'posts' => $posts, 
            'category' => $category, 
            'class' => $this->class
        ]);
    }
}