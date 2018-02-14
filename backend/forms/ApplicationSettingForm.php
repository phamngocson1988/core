<?php

namespace backend\forms;

use Yii;
use yii\base\Model;

class ApplicationSettingForm extends Model
{
    public $contact_phone;
    public $contact_email;
    public $admin_email;
    public $enable_subscribe;

    public function init()
    {
        parent::init();
        if (!$this->enable_subscribe) {
            $this->enable_subscribe = 'N';
        }
    }

    public function rules()
    {
        return [
            [['contact_phone', 'contact_email', 'admin_email', 'enable_subscribe'], 'trim'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'contact_phone' => Yii::t('app', 'contact_phone'),
            'contact_email' => Yii::t('app', 'contact_email'),
            'admin_email' => Yii::t('app', 'admin_email'),
            'enable_subscribe' => 'Show subscribe Popup'
        ];
    }
}