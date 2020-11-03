<?php

namespace frontend\forms;

use Yii;
use yii\base\Model;
use frontend\models\Mail;
use frontend\models\MailThread;
use frontend\models\User;

class ComposeMailForm extends Model
{
    public $subject;
    public $content;
    public $toEmail;

    protected $_receiver;

    public function rules()
    {
        return [
            [['subject', 'content', 'toEmail'], 'required'],
            ['toEmail', 'validateTo']
        ];
    }

    public function attributeLabels()
    {
        return [
            'subject' => Yii::t('app', 'Subject'),
            'content' => Yii::t('app', 'Content'),
        ];
    }

    public function validateTo($attribute, $params = [])
    {
        $user = $this->getReceiver();
        if (!$user) {
            $this->addError($attribute, 'Receiver is not exist in system');
            return;
        }
    }

    public function getReceiver()
    {
        if (!$this->_receiver) {
            $this->_receiver = User::find()->where(['email' => $this->toEmail])->one();
        }
        return $this->_receiver;
    }

    public function compose()
    {
        $user = $this->getReceiver();
        $connection = Yii::$app->db;
        $transaction = $connection->beginTransaction();
        try {
            $mailThread = new MailThread();
            $mailThread->subject = $this->subject;
            $mailThread->to = $user->id;
            $mailThread->save();

            $mail = new Mail();
            $mail->mail_thread_id = $mailThread->id;
            $mail->content = $this->content;
            $mail->save();

            $transaction->commit();
            return true;
        } catch(Exception $e) {
            $transaction->rollback();
            $this->addError('id', $e->getMessage());
            return false;
        }
    }

}
