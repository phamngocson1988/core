<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Operator;
use frontend\models\OperatorReview;
use frontend\models\UserBadge;

class AddOperatorReviewForm extends Model
{
    public $user_id;
    public $operator_id;
    public $good_thing;
    public $bad_thing;
    public $star;
    public $notify_register;
    public $experience;

    protected $_user;
    protected $_operator;

    public function rules()
    {
        return [
            [['user_id', 'operator_id', 'star'], 'required'],
            ['user_id', 'validateUser'],
            ['operator_id', 'validateOperator'],
            [['good_thing', 'bad_thing'], 'required'],
            ['star', 'number', 'max' => 10, 'min' => 1],
            [['notify_register', 'experience'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['notify_register', 'experience'], 'default', 'value' => false],
            ['experience', 'validateExperience']
        ];
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'User is not exist'));
        }
        if ($user->isReview($this->operator_id)) {
            $this->addError($attribute, Yii::t('app', 'You reviewed this operator'));
        }
    }

    public function validateExperience($attribute, $params = []) 
    {
        if (!$this->experience) {
            $this->addError($attribute, 'You have to confirm that this review is base on your own experience');
        }
    }

    public function validateOperator($attribute, $params = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'Operator is not exist'));
        }
    }

    public function add()
    {
        $user = $this->getUser();
        $operator = $this->getOperator();

        $subscribedItems = OperatorReview::find()->where([
            'operator_id' => $this->operator_id,
            'notify_register' => 1
        ])->with('user')->all();
        $emailSubscribers = [];
        foreach ($subscribedItems as $item) {
            $subscribedUser = $item->user;
            $emailSubscribers[] = $subscribedUser->email;
        }

        $review = new OperatorReview();
        $review->user_id = $this->user_id;
        $review->operator_id = $this->operator_id;
        $review->good_thing = $this->good_thing;
        $review->bad_thing = $this->bad_thing;
        $review->star = $this->star;
        $review->notify_register = $this->notify_register;
        $review->experience = $this->experience;
        $user->addBadge(UserBadge::BADGE_REVIEW, $user->id, sprintf("%s - %s stars", $operator->name, $review->star));
        $user->plusPoint(100, sprintf("New review for %s", $operator->name));
        if (count($emailSubscribers)) {
            Yii::$app->mailer->compose('notify_new_review_operator', ['operator' => $operator])
            ->setTo($emailSubscribers)
            ->setFrom(['bw2020@support.com' => 'BW2020 Customer Service'])
            ->setSubject(sprintf("New Operator Review - %s", $operator->name))
            ->send();
        }
        return $review->save();
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->operator_id);
        }
        return $this->_operator;
    }

}
