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
    public $accountant_email;
    public $customer_service_email;
    public $supplier_service_email;
    public $enable_subscribe;
    public $exchange_rate_cny; 
    public $exchange_rate_vnd; 
    public $managing_cost_rate; 
    public $investing_cost_rate; 
    public $desired_profit; 
    public $reseller_desired_profit; 
    public $logo;
    public $affiliate_banner;
    public $affiliate_banner_mobile;
    public $affiliate_banner_link;
    public $refer_banner;
    public $refer_banner_mobile;
    public $refer_banner_link;
    public $kcoin_banner;
    public $kcoin_banner_mobile;
    public $kcoin_banner_link;
    public $am_commission_rate;
    public $ot_commission_rate;

    //post banner
    public $post_banner;

    //hot-new
    public $hot_post_id;

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
            [['admin_email', 'contact_email', 'accountant_email'], 'email'],
            ['exchange_rate_cny', 'number'],
            [['logo', 'customer_service_email', 'supplier_service_email'], 'safe'],
            [['managing_cost_rate', 'investing_cost_rate', 'desired_profit', 'reseller_desired_profit'], 'number'],
            [['affiliate_banner', 'affiliate_banner_mobile', 'affiliate_banner_link', 'refer_banner', 'refer_banner_mobile', 'refer_banner_link', 'kcoin_banner', 'kcoin_banner_mobile', 'kcoin_banner_link'], 'safe'],
            ['post_banner', 'safe'],
            ['hot_post_id', 'safe'],
            [['am_commission_rate', 'ot_commission_rate'], 'number', 'max' => 100],
            [['am_commission_rate', 'ot_commission_rate'], 'validateCommissionRate'],
        ];
    }

    public function validateCommissionRate($attribute, $params)
    {
        if (($this->am_commission_rate + $this->ot_commission_rate) > 100) {
            return $this->addError($attribute, 'Tổng tỉ lệ hoa hồng  của AM và OT phải nhỏ hơn 100%');
        }
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
            'supplier_service_email' => 'Email dùng cho nhà cung cấp',
            'admin_email' => Yii::t('app', 'admin_email'),
            'enable_subscribe' => 'Show subscribe Popup',
            'exchange_rate_cny' => 'Tỷ giá CNY',
            'exchange_rate_vnd' => 'Tỷ giá VNĐ',
            'managing_cost_rate' => 'Tỷ lệ chi phí quản lý',
            'investing_cost_rate' => 'Tỷ lệ chi phí đầu tư',
            'desired_profit' => 'Lợi nhuận mong muốn',
            'reseller_desired_profit' => 'Lợi nhuận mong muốn từ nhà bán lẻ',
            'logo' => 'Logo',
            'affiliate_banner' => 'Banner for affiliate',
            'affiliate_banner_mobile' => 'Banner for affiliate (mobile)',
            'affiliate_banner_link' => 'Link for affiliate banner',
            'refer_banner' => 'Banner for Referral Friend',
            'refer_banner_mobile' => 'Banner for Referral Friend (mobile)',
            'refer_banner_link' => 'Link for Referral Friend banner',
            'kcoin_banner' => 'Banner for KCoin',
            'kcoin_banner_mobile' => 'Banner for KCoin (mobile)',
            'kcoin_banner_link' => 'Link for KCoin banner',
            'accountant_email' => 'Email kế toán',
            'post_banner' => 'Banner trang tin tức',
            'hot_post_id' => 'Hot new trang chủ',
            'am_commission_rate' => 'Phần trăm hoa hồng cho AM (%)',
            'ot_commission_rate' => 'Phần trăm hoa hồng cho OT (%)',
        ];
    }

    public function fetchPosts()
    {
        $form = new \backend\forms\FetchPostForm();
        $command = $form->getCommand();
        $posts = $command->all();
        return ArrayHelper::map($posts, 'id', 'title');
    }
}