<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use common\models\PostComment;

/**
 * CreatePostCommentForm is the model behind the contact form.
 */
class CreatePostCommentForm extends Model
{
    public $post_id;
    public $content;
    public $user_id;
    public $parent_id;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['post_id', 'content', 'user_id'], 'required'],
            ['parent_id', 'safe'],
        ];
    }

    public function save()
    {
        if (!$this->validate()) return false;
        $commentModel = new PostComment([
            'post_id' => $this->post_id,
            'comment' => $this->content,
            'created_by' => $this->user_id,
            'parent_id' => $this->parent_id
        ]);
        if ($commentModel->save()) {
            return $commentModel;
        }
        return false;
    }
}
