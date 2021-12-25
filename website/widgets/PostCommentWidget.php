<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use yii\helpers\Url;

class PostCommentWidget extends Widget
{
    public $post_id;
    public function run()
    {
        if (!$this->post_id) return;
        return $this->render('post-comments');
    }
}