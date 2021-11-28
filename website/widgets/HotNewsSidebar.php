<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class HotNewsSidebar extends Widget
{
    public function run()
    {
        $form = new \website\forms\FetchPostForm(['hot' => 1]);
        $command = $form->getCommand();
        $models = $command->limit(5)
            ->orderBy(['position' => SORT_DESC])
            ->all();
        return $this->render('hot-new-sidebar', [
            'models' => $models, 
        ]);
    }

}