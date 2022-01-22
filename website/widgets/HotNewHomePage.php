<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use website\models\Post;

class HotNewHomePage extends Widget
{
    public function run()
    {
        $settings = Yii::$app->settings;
        $id = $settings->get('ApplicationSettingForm', 'hot_post_id');
        if (!$id) return '';
        $model = Post::findOne($id);
        if (!$model) return '';
        return $this->render('hot-new-homepage', [
            'model' => $model, 
        ]);
    }

}