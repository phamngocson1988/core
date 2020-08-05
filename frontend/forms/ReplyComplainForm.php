<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\ComplainReply;
use frontend\models\Complain;
use yii\helpers\ArrayHelper;
use frontend\components\notifications\ComplainNotification;

class ReplyComplainForm extends Model
{
    public $user_id;
    public $complain_id;
    public $mark_close;
    public $description;
    public $operator_id;

    protected $_complain;

    public function rules()
    {
        return [
            [['user_id', 'complain_id', 'mark_close', 'description'], 'required'],
            ['complain_id', 'validateComplain'],
            ['operator_id', 'safe']
        ];
    }

    public function validateComplain($attribute, $params = [])
    {
        $complain = $this->getComplain();
        if (!$complain) {
            $this->addError($attribute, Yii::t('app', 'complain_is_not_exist'));
        } elseif (!$complain->isOpen()) {
            $this->addError($attribute, Yii::t('app', 'complain_is_not_available_to_reply'));
        }
    }

    public function getComplain()
    {
        if (!$this->_complain) {
            $this->_complain = Complain::findOne($this->complain_id);
        }
        return $this->_complain;
    }

    public function attributeLabels()
    {
        return [
            'mark_close' => Yii::t('app', 'complain_mark_close'),
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
