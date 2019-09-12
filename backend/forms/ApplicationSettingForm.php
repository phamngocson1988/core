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
    public $customer_service_email;
    public $enable_subscribe;
    public $exchange_rate_cny; 
    public $exchange_rate_vnd; 
    public $managing_cost_rate; 
    public $investing_cost_rate; 
    public $desired_profit; 
    public $reseller_desired_profit; 
    public $logo;

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
            [['contact_phone', 'contact_email', 'admin_email', 'enable_subscribe', 'exchange_rate_cny', 'exchange_rate_vnd'], 'trim'],
            [['admin_email', 'contact_email'], 'email'],
            ['exchange_rate_cny', 'number'],
            [['logo', 'customer_service_email'], 'safe'],
            [['managing_cost_rate', 'investing_cost_rate', 'desired_profit', 'reseller_desired_profit'], 'number'],
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
            'customer_service_email' => 'Email chăm sóc khách hàng',
            'admin_email' => Yii::t('app', 'admin_email'),
            'enable_subscribe' => 'Show subscribe Popup',
            'exchange_rate_cny' => 'Tỷ giá CNY',
            'exchange_rate_vnd' => 'Tỷ giá VNĐ',
            'managing_cost_rate' => 'Tỷ lệ chi phí quản lý',
            'investing_cost_rate' => 'Tỷ lệ chi phí đầu tư',
            'desired_profit' => 'Lợi nhuận mong muốn',
            'reseller_desired_profit' => 'Lợi nhuận mong muốn từ nhà bán lẻ',
            'logo' => 'Logo',
        ];
    }
}