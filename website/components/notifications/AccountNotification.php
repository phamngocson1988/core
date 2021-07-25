<?php 
namespace website\components\notifications;

use Yii;
use webzop\notifications\Notification;
use yii\helpers\ArrayHelper;
use website\models\User;

class AccountNotification extends Notification
{
    const NOTIFY_STAFF_NEW_ACCOUNT = 'NOTIFY_STAFF_NEW_ACCOUNT';

    /**
     * @var \yii\web\User the user object
     */
    public $account;

    /**
     * @inheritdoc
     */
    public function getTitle(){
        switch($this->key){
            case self::NOTIFY_STAFF_NEW_ACCOUNT:
                return 'Khách hàng mới tạo tài khoản';
        }
    }

    /**
     * @inheritdoc
     */
    public function getRoute(){
        switch($this->key){
            case self::NOTIFY_STAFF_NEW_ACCOUNT:
                return 'javascript:;';
        }
    }

    public function getIcon()
    {
        switch ($this->key) {
            case self::NOTIFY_STAFF_NEW_ACCOUNT:
                return 'https://kinggems.us/images/logo_icon.png';

        }
    }

    public function getDescription()
    {

        switch ($this->key) {
            case self::NOTIFY_STAFF_NEW_ACCOUNT:
                return "Bộ phận chăm sóc khách hàng vui lòng kiểm tra thông tin và hỗ trợ khách hàng.";
        }
    }

    public function shouldSend($channel)
    {
        $allows = $this->allow();
        $allowByChannel = ArrayHelper::getValue($allows, $channel->id, []);
        return in_array($this->key, $allowByChannel);
    }

    protected function allow()
    {
        return [
            'desktop' => [
                self::NOTIFY_STAFF_NEW_ACCOUNT,
            ],
            'email' => [
                self::NOTIFY_STAFF_NEW_ACCOUNT,
            ],

        ];
    }

    public function toEmail($channel)
    {
        $account = $this->account; // User
        $kinggemsMailer = Yii::$app->mailer;
        $user = User::findOne($this->userId);
        $toEmail = $user->email;
        $subject = '';
        $template = '';
        switch ($this->key) {
            case self::NOTIFY_STAFF_NEW_ACCOUNT:
                $subject = sprintf('King Gems - %s', $this->getTitle());
                $template = 'new_account';
                break;

        }


        $emailHelper = new \common\components\helpers\MailHelper();
        $emailHelper
        ->setMailer($kinggemsMailer)
        ->usingCustomerService()
        ->usingKinggemsSiteName()
        ->send(
            $subject,
            $toEmail,
            $template,
            [
                'account' => $account,
                'user' => $user
            ]
        );
    }
}