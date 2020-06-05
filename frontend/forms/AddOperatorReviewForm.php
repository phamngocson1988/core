<?php
namespace frontend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use frontend\models\User;
use frontend\models\Operator;
use frontend\models\OperatorReview;

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
            [['good_thing', 'bad_thing'], 'trim'],
            ['star', 'number', 'max' => 10, 'min' => 1],
            [['notify_register', 'experience'], 'boolean', 'trueValue' => true, 'falseValue' => false],
            [['notify_register', 'experience'], 'default', 'value' => false],
        ];
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            $this->addError($attribute, Yii::t('app', 'user_is_not_exist'));
        }
        if ($user->isReview($this->operator_id)) {
            $this->addError($attribute, Yii::t('app', 'you_reviewed_operator'));
        }
    }

    public function validateOperator($attribute, $params = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'operator_is_not_exist'));
        }
    }

    public function add()
    {
        $review = new OperatorReview();
        $review->user_id = $this->user_id;
        $review->operator_id = $this->operator_id;
        $review->good_thing = $this->good_thing;
        $review->bad_thing = $this->bad_thing;
        $review->star = $this->star;
        $review->notify_register = $this->notify_register;
        $review->experience = $this->experience;
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
