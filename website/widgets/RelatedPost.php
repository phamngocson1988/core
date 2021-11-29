<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use common\models\Post;
use common\models\PostCategory;

class RelatedPost extends Widget
{
    public $post_id;
    public function run()
    {
        if (!$this->post_id) return;
        $postTable = Post::tableName();
        $categoryTable = PostCategory::tableName();

        $models = Post::find()
        ->innerJoin($categoryTable, "$postTable.id = $categoryTable.post_id")
        ->where(['<>', 'id', $this->post_id])
        ->orderBy(['id' => SORT_DESC])
        ->limit(3)
        ->all();
        if (!count($models)) return;
        return $this->render('related-post', [
            'models' => $models, 
        ]);
    }

}