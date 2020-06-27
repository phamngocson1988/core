<?php
namespace common\behaviors;

use Yii;
use yii\behaviors\AttributeBehavior;
use yii\helpers\ArrayHelper;
use common\models\MailThread;
use common\models\Mail;
use common\models\User;

class MailboxBehavior extends AttributeBehavior
{
    public function getSender() 
    {
        $owner = $this->owner; // MailThread
        return $owner->hasOne(User::className(), ['id' => 'from']);
    }

    public function getReceiver()
    {
        $owner = $this->owner; // MailThread
        return $owner->hasOne(User::className(), ['id' => 'to']);
    }

    public function getLastMail()
    {
    	$owner = $this->owner;
    	return Mail::find()
    	->where(['mail_thread_id' => $owner->id])
    	->orderBy(['id' => SORT_DESC])
    	->one();
    }
}
