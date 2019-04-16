<?php



namespace frontend\forms;



use Yii;

use yii\base\Model;



/**

 * ContactForm is the model behind the contact form.

 */

class ContactForm extends Model

{

    public $name;

    public $email;

    public $phone;

    public $subject;

    public $body;

    public $verifyCode;





    /**

     * @inheritdoc

     */

    public function rules()

    {

        return [

            // name, email, subject and body are required

            [['name', 'email', 'subject', 'body'], 'required'],

            // email has to be a valid email address

            ['email', 'email'],

            ['phone', 'trim'],

            // verifyCode needs to be entered correctly

            ['verifyCode', 'captcha'],

        ];

    }



    public function attributeLabels()

    {

        return [

            'name' => 'Tên khách hàng',

            'email' => 'Email',

            'phone' => 'Số điện thoại',

            'subject' => 'Tiêu đề',

            'body' => 'Nội dung'

        ];

    }



    /**

     * Sends an email to the specified email address using the information collected by this model.

     *

     * @param string $email the target email address

     * @return bool whether the email was sent

     */

    public function sendEmail($email)

    {

        $settings = Yii::$app->settings;

        $contactEmail = $settings->get('ApplicationSettingForm', 'contact_email', $email);



        // Send email to administrators

        return Yii::$app->mailer->compose('supporter_contact_us', ['mail' => $this])

            ->setTo($contactEmail)

            ->setFrom([$this->email => $this->name])

            ->setSubject("[Global Prepaidcard][Contact Email] Bạn nhận được yêu cầu liên hệ từ " . $this->name)

            ->setTextBody($this->body)

            ->send();

    }

}

