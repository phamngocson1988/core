<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\OperatorReview;
use frontend\components\notifications\ReviewNotification;

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
            $this->addError($attribute, Yii::t('app', 'Review is not exist'));
            return;
        }
        if ($review->reply) {
            $this->addError($attribute, Yii::t('app', 'Cannot reply on this review'));
            return;
        }
    }

    public function reply()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $review = $this->getReview();
            $review->reply = $this->reply;
            $review->replied_by = $this->user_id;
            $review->replied_at = date('Y-m-d H:i:s');
            if ($review->save()) {
                ReviewNotification::create(ReviewNotification::OPERATOR_RESPONSE, [
                    'review' => $review,
                    'userId' => $review->created_by
                ])->send();
                $transaction->commit();
                return true;
            }
            $transaction->rollback();
            return false;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }

        
    }

    public function getReview()
    {
        if (!$this->_review) {
            $this->_review = OperatorReview::findOne($this->id);
        }
        return $this->_review;
    }
}
