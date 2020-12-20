<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\ComplainReply;
use frontend\models\Complain;
use frontend\models\User;
use frontend\models\OperatorStaff;
use yii\helpers\ArrayHelper;
use frontend\components\notifications\ComplainNotification;

class OperatorReplyComplainForm extends Model
{
    public $user_id;
    public $complain_id;
    public $mark_close;
    public $description;
    public $operator_id;

    protected $_complain;
    protected $_user;

    public function rules()
    {
        return [
            [['user_id', 'complain_id', 'mark_close', 'description'], 'required'],
            ['user_id', 'validateUser'],
            ['complain_id', 'validateComplain'],
            ['operator_id', 'safe']
        ];
    }

    public function validateComplain($attribute, $params = [])
    {
        $complain = $this->getComplain();
        if (!$complain) {
            $this->addError($attribute, Yii::t('app', 'Complaint is not exist'));
        } elseif (!$complain->isOpen()) {
            $this->addError($attribute, Yii::t('app', 'Complaint is not available to reply'));
        }
    }

    public function validateUser($attribute, $params = [])
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->addError($attribute, Yii::t('app', 'User is not exist'));
        }
        if (!$user->isOperatorStaffOf($this->operator_id, OperatorStaff::ROLE_ADMIN) && !$user->isOperatorStaffOf($this->operator_id, OperatorStaff::ROLE_SUBADMIN)) {
            $this->addError($attribute, Yii::t('app', 'You are not enough permission to reply this complain'));
        }
    }

    public function getComplain()
    {
        if (!$this->_complain) {
            $this->_complain = Complain::findOne($this->complain_id);
        }
        return $this->_complain;
    }

    public function getUser()
    {
        if (!$this->_user) {
            $this->_user = User::findOne($this->user_id);
        }
        return $this->_user;
    }

    public function attributeLabels()
    {
        return [
            'mark_close' => Yii::t('app', 'Complaint mark close'),
        ];
    }
    
    public function add()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $reply = new ComplainReply();
            $reply->description = $this->description;
            $reply->user_id = $this->user_id;
            $reply->complain_id = $this->complain_id;
            $reply->mark_close = $this->mark_close;
            $reply->operator_id = $this->operator_id;
            if ($reply->save()) {
                $complain = $this->getComplain();
                if ($this->mark_close) {
                    $complain->status = Complain::STATUS_RESOLVE;
                    $complain->save();

                    ComplainNotification::create(ComplainNotification::COMPLAIN_RESOLVED, [
                        'complain' => $complain,
                        'userId' => $reply->user_id
                    ])->send();
                } elseif ($this->operator_id) { 
                    ComplainNotification::create(ComplainNotification::OPERATOR_RESPONSE, [
                        'complain' => $complain,
                        'userId' => $complain->user_id
                    ])->send();
                }
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
}
