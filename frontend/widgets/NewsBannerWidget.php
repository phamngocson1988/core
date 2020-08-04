<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Post;

class NewsBannerWidget extends Widget
{
    public function run()
    {
        $newestNews = Post::find()->limit(3)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('news-banner', [
            'newestNews' => $newestNews, 
        ]);
    }
}