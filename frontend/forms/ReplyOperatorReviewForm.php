<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\OperatorReview;

class ReplyOperatorReviewForm extends Model
{
    public $id;
    public $reply;
    public $user_id;

    protected $_review;
    protected $_user;

    public function rules()
    {
        return [
            ['reply', 'trim'],
            [['id', 'reply', 'user_id'], 'required'],
            ['id', 'validateReview'],
        ];
    }

    public function validateReview($attribute, $params = [])
    {
        $review = $this->getReview();
        if (!$review) {
            $this->addError($attribute, Yii::t('app', 'review_is_not_exist'));
            return;
        }
        if ($review->reply) {
            $this->addError($attribute, Yii::t('app', 'cannot_reply_on_this_review'));
            return;
        }
    }

    public function reply()
    {
        $review = $this->getReview();
        $review->reply = $this->reply;
        $review->replied_by = $this->user_id;
        $review->replied_at = date('Y-m-d H:i:s');
        return $review->save();
    }

    public function getReview()
    {
        if (!$this->_review) {
            $this->_review = OperatorReview::findOne($this->id);
        }
        return $this->_review;
    }
}
