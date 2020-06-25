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

    public function attributeLabels()
    {
        return [
            'content' => Yii::t('app', 'content'),
        ];
    }

    public function reply()
    {
        $mail = new Mail();
        $mail->mail_thread_id = $this->thread_id;
        $mail->content = $this->content;
        return $mail->save();
    }

}
