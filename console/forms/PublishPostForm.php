<?php

namespace console\forms;

use Yii;
use common\models\Post;

class PublishPostForm extends ActionForm
{
    /**
     * TODO: 
     * - Fetch scheduled posts
     * - Check publish_at time
     * - Publish valid posts
     */

    public function run() 
    {
        $current = date('Y-m-d H:i:s');
        $models = Post::find()
        ->where(['status' => Post::STATUS_SCHEDULED])
        ->andWhere(['<=', 'published_at', $current])
        ->all();
        foreach ($models as $model) {
            $model->status = Post::STATUS_VISIBLE;
            $model->created_at = $current;
            $model->save();
        }
        return count($models);
    }
}
