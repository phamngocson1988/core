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
        $form = new \frontend\forms\FetchPostForm(['category_id' => $this->categoryId]);
        $command = $form->getCommand();
        $posts = $command->orderBy(['id' => SORT_DESC])->limit(5)->all();
        $category = Category::findOne($this->categoryId);
        return $this->render('news-by-category', [
            'posts' => $posts, 
            'category' => $category, 
            'class' => $this->class
        ]);
    }
}