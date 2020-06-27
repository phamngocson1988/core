<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Mail;
use frontend\models\MailThread;

class ReplyMailForm extends Model
{
    public $thread_id;
    public $content;

    protected $_thread;

    public function rules()
    {
        return [
            [['thread_id', 'content'], 'required'],
            ['thread_id', 'validateThread']
        ];
    }

    public function validateThread($attribute, $params = [])
    {
        $thread = $this->getThread();
        if (!$thread) {
            $this->addError($attribute, 'This mail is not exist');
            return;
        } else {
            if (!in_array(Yii::$app->user->id, [$thread->from, $thread->to])) {
                $this->addError($attribute, 'This mail is invalid');
            }
        }
    }

    public function getThread()
    {
        if (!$this->_thread) {
            $this->_thread = MailThread::findOne($this->thread_id);
        }
        return $this->_thread;
    }

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('app', 'content'),
        ];
    }

    public function reply()
    {
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $mail = new Mail();
            $mail->mail_thread_id = $this->thread_id;
            $mail->content = $this->content;
            $mail->save();

            $thread = $this->getThread();
            $thread->touch('updated_at');
            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('content', $e->getMessage());
            return false;
        }
    }

}
