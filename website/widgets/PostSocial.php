<?php
namespace website\widgets;

use Yii;
use yii\base\Widget;
use common\models\PostLike;

class PostSocial extends Widget
{
    public $post_id;
    public $view_count = 0;
    public $share_link;
    
    public function run()
    {
        if (!$this->post_id) return;
        $likes = PostLike::find()->where(['post_id' => $this->post_id])->count();
        $canLike = !Yii::$app->user->isGuest;
        $isLike = !$canLike ? false : 
            PostLike::find()->where(['post_id' => $this->post_id, 'user_id' => Yii::$app->user->id])->exists();
        return $this->render('post-social', [
            'likes' => $likes, 
            'view_count' => $this->view_count,
            'post_id' => $this->post_id,
            'canLike' => $canLike,
            'isLike' => $isLike,
            'share_link' => $this->share_link
        ]);
    }

}