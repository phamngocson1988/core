<?php

namespace backend\forms;

use Yii;
use yii\base\Model;
use yii\helpers\ArrayHelper;
use backend\models\Promotion;

class ApplicationSettingForm extends Model
{
    public $contact_phone;
    public $contact_email;
    public $admin_email;
    public $enable_subscribe;
    public $exchange_rate_usd; 
    public $sign_on_bonus;

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
            [['contact_phone', 'contact_email', 'admin_email', 'enable_subscribe', 'exchange_rate_usd'], 'trim'],
            [['admin_email', 'contact_email'], 'email'],
            ['exchange_rate_usd', 'number'],
            ['exchange_rate_usd', 'default', 'value' => '22000'],
            [['sign_on_bonus'], 'safe'],
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
            'enable_subscribe' => 'Show subscribe Popup',
            'exchange_rate_usd' => 'Tỷ giá USD',
            'sign_on_bonus' => 'Khuyến mãi khi đăng ký',
        ];
    }

    public function getPromotions()
    {
        $command = Promotion::findAvailable();
        $command->select(['id', 'title']);
        return ArrayHelper::map($command->all(), 'id', 'title');
    }
}