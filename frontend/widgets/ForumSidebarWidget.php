<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use frontend\models\ForumTopic;

class ForumSidebarWidget extends Widget
{
    public function run()
    {
        $topics = ForumTopic::find()->limit(5)->orderBy(['id' => SORT_DESC])->all();
        if (!count($topics)) return '';

        return $this->render('forum_sidebar', [
            'topics' => $topics, 
        ]);
    }
}