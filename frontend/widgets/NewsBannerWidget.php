<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\Operator;
use frontend\models\Post;

class NewsBannerWidget extends Widget
{
    public function run()
    {
        $newestNews = Post::find()->limit(4)->orderBy(['id' => SORT_DESC])->all();
        $topOperators = Operator::find()->limit(10)->orderBy(['id' => SORT_DESC])->all();
        return $this->render('news-banner', [
            'topOperators' => $topOperators,
            'newestNews' => $newestNews, 
        ]);
    }
}