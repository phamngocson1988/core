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
    public $sign_on_bonus;
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
            [['contact_phone', 'contact_email', 'admin_email', 'enable_subscribe', 'exchange_rate_cny'], 'trim'],
            [['admin_email', 'contact_email'], 'email'],
            ['exchange_rate_cny', 'number'],
            [['sign_on_bonus', 'logo', 'customer_service_email'], 'safe'],
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
            'sign_on_bonus' => 'Khuyến mãi khi đăng ký',
            'logo' => 'Logo',
        ];
    }

    public function getPromotions()
    {
        $command = Promotion::find();
        $command->select(['id', 'title']);
        $now = date('Y-m-d');
        $command->where([
            'status' => Promotion::STATUS_VISIBLE,
            'is_valid' => Promotion::IS_VALID,
        ]);
        $command->andWhere(['OR', 
            ['<=', 'from_date', $now],
            ['from_date' => null]
        ]);
        $command->andWhere(['OR', 
            ['>=', 'to_date', $now],
            ['to_date' => null]
        ]);
        return ArrayHelper::map($command->all(), 'id', 'title');
    }
}