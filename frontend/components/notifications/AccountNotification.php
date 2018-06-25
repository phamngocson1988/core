<?php
namespace frontend\components\notifications;

use Yii;
use webzop\notifications\Notification;

class AccountNotification extends Notification
{
    const KEY_NEW_ACCOUNT = 'new_account';

    const KEY_RESET_PASSWORD = 'reset_password';

    /**
     * @var \yii\web\User the user object
     */
    public $user;

    public $userId;

    public function init()
    {
        parent::init();
        $this->userId = $this->user->id;
    }

    /**
     * @inheritdoc
     */
    public function getTitle(){
        switch($this->key){
            case self::KEY_NEW_ACCOUNT:
                return Yii::t('app', 'New account {user} created', ['user' => '#'.$this->user->id]);
            case self::KEY_RESET_PASSWORD:
                return Yii::t('app', 'Instructions to reset the password');
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        return ['/users/edit', 'id' => $this->user->id];
    }
}