<?php
use yii\helpers\Html;
$setting = Yii::$app->settings;
$banks = [
    [
        'bank_name' => $setting->get('OfflinePaymentSettingForm', 'bank_name1'),
        'account_number' => $setting->get('OfflinePaymentSettingForm', 'account_number1'),
        'account_holder' => $setting->get('OfflinePaymentSettingForm', 'account_holder1'),
    ],
    [
        'bank_name' => $setting->get('OfflinePaymentSettingForm', 'bank_name2'),
        'account_number' => $setting->get('OfflinePaymentSettingForm', 'account_number2'),
        'account_holder' => $setting->get('OfflinePaymentSettingForm', 'account_holder2'),
    ],
    [
        'bank_name' => $setting->get('OfflinePaymentSettingForm', 'bank_name3'),
        'account_number' => $setting->get('OfflinePaymentSettingForm', 'account_number3'),
        'account_holder' => $setting->get('OfflinePaymentSettingForm', 'account_holder3'),
    ],
    [
        'bank_name' => $setting->get('OfflinePaymentSettingForm', 'bank_name4'),
        'account_number' => $setting->get('OfflinePaymentSettingForm', 'account_number4'),
        'account_holder' => $setting->get('OfflinePaymentSettingForm', 'account_holder4'),
    ]
];
$banks = array_filter($banks, function($data) {
    return ($data['bank_name'] && $data['account_number'] && $data['account_holder']);
});
?>

<?php foreach ($banks as $bank) : ?>
Bank name: <?=$bank['bank_name'];?><br/>
Account number: <?=$bank['account_number'];?><br/>
Account holder: <?=$bank['account_holder'];?><br/>
<?php endforeach;?>
<h3>Note: "KINGGEMS <?=$transaction->auth_key;?>"</h3>