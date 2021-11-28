<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class PostCategorySidebar extends Widget
{
    public function run()
    {
        $fetchCategoryForm = new \website\forms\FetchCategoryForm([
            'type' => \common\models\Category::TYPE_POST,
            'visible' => \common\models\Category::VISIBLE
        ]);
        $categories = $fetchCategoryForm->fetch();
        return $this->render('post-category-sidebar', [
            'categories' => $categories, 
        ]);
    }

}