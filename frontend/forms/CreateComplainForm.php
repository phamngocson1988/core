<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Complain;
use frontend\models\Operator;
use frontend\models\ComplainReason;
use frontend\models\UserBadge;
use frontend\models\User;
use yii\helpers\ArrayHelper;
use frontend\components\notifications\ComplainNotification;

class CreateComplainForm extends Model
{
    public $user_id;
    public $operator_id;
    public $reason_id;
    public $title;
    public $description;
    public $account_name;
    public $account_email;
    public $agree;

    protected $_operator;

    public function rules()
    {
        return [
            [['user_id', 'operator_id', 'reason_id', 'title', 'description', 'account_name', 'account_email', 'agree'], 'required'],
            ['agree', 'boolean'],
            ['agree', 'validateAgree'],
            ['operator_id', 'validateOperator'],
        ];
    }

    public function attributeLabels()
    {
        return [
            'operator_id' => Yii::t('app', 'select_operator'),
            'reason_id' => Yii::t('app', 'select_an_option_that_best_describe_the_issue'),
            'title' => Yii::t('app', 'complain_title'),
            'account_name' => Yii::t('app', 'account_name_used_at_this_operator'),
            'account_email' => Yii::t('app', 'email_registered_with_this_operator'),
        ];
    }

    public function validateAgree($attribute, $params = [])
    {
        if (!$this->agree) {
            $this->addError($attribute, Yii::t('app', 'you_need_to_agree_our_term_and_policy'));
        }
    }

    public function validateOperator($attribute, $params = [])
    {
        $operator = $this->getOperator();
        if (!$operator) {
            $this->addError($attribute, Yii::t('app', 'operator_is_not_exist'));
        }
    }
    
    public function create()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $complain = new Complain();
            $complain->title = $this->title;
            $complain->description = $this->description;
            $complain->user_id = $this->user_id;
            $complain->reason_id = $this->reason_id;
            $complain->operator_id = $this->operator_id;
            $complain->account_name = $this->account_name;
            $complain->account_email = $this->account_email;
            if ($complain->save()) {
                $user = $this->getUser();
                $operator = $this->getOperator();
                $user->addBadge(UserBadge::BADGE_COMPLAIN, $complain->id, sprintf("%s - %s", $operator->name, $complain->title));

                $operatorStaffs = User::find()->where(['operator_id' => $this->operator_id])->all();
                foreach ($operatorStaffs as $operatorStaff) {
                    ComplainNotification::create(ComplainNotification::NEW_COMPLAIN_PUBLISHED, [
                        'complain' => $complain,
                        'userId' => $operatorStaff->id
                    ])->send();
                }

                $transaction->commit();
                return $complain;
            }
            $transaction->rollback();
            return false;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
        
    }

    public function fetchReason()
    {
        $categories = ComplainReason::find()->select(['id', 'title'])->all();
        return ArrayHelper::map($categories, 'id', 'title');
    }

    public function fetchOperator()
    {
        $operators = Operator::find()->select(['id', 'name'])->all();
        return ArrayHelper::map($operators, 'id', 'name');
    }

    public function getOperator()
    {
        if (!$this->_operator) {
            $this->_operator = Operator::findOne($this->operator_id);
        }
        return $this->_operator;
    }

    public function getUser()
    {
        return Yii::$app->user->getIdentity();
    }

}
