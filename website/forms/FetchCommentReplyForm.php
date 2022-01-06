<?php

namespace website\forms;

use Yii;
use yii\base\Model;
use common\models\PostComment;

/**
 * FetchCommentReplyForm is the model behind the contact form.
 */
class FetchCommentReplyForm extends Model
{
    public $id;

    protected $_comment;

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['id', 'required'],
            // ['id', 'validateComment'],
        ];
    }

    public function validateComment($attribute, $params) {
        $comment = $this.getComment();
        if (!$comment) {
            $this->addError($attribute, 'Comment is not exist');
        }
    }

    public function getComment()
    {
        if (!$this->_comment) {
            $this->_comment = PostComment::findOne($this->id);
        }
    }

    public function fetch()
    {
        if (!$this->validate()) return false;
        return PostComment::find()->where(['parent_id' => $this->id])->all();
    }
}
