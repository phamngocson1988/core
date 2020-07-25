<?php
namespace frontend\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use frontend\models\ForumTopic;
use frontend\models\ForumSection;
use frontend\models\ForumCategory;
use frontend\models\ForumSectionCategory;

class ForumTopContributorWidget extends Widget
{
    public function run()
    {
        return $this->render('forum_topcontributor', [
        ]);
    }
}