<?php
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

/**
 * Sample payload
 * {"status":true,"post":{"create_time":"2019-11-28T17:28:30Z","update_time":"2019-11-28T17:29:02Z","id":"48N5803021263831D","intent":"CAPTURE","status":"COMPLETED","payer":{"email_address":"kinggems_richkid@gmail.com","payer_id":"XN62AK3SNLXZA","address":{"country_code":"US"},"name":{"given_name":"Kinggem","surname":"Rich Kid"}},"purchase_units":[{"reference_id":"default","amount":{"value":"0.10","currency_code":"USD"},"payee":{"email_address":"quickmoney.business@gmail.com","merchant_id":"3QT8N4EAHBSVS"},"shipping":{"name":{"full_name":"Kinggem Rich Kid"},"address":{"address_line_1":"1 Main St","admin_area_2":"San Jose","admin_area_1":"CA","postal_code":"95131","country_code":"US"}},"payments":{"captures":[{"status":"COMPLETED","id":"0CC69994SP633913N","final_capture":"true","create_time":"2019-11-28T17:29:02Z","update_time":"2019-11-28T17:29:02Z","amount":{"value":"0.10","currency_code":"USD"},"seller_protection":{"status":"ELIGIBLE","dispute_categories":["ITEM_NOT_RECEIVED","UNAUTHORIZED_TRANSACTION"]},"links":[{"href":"https://api.sandbox.paypal.com/v2/payments/captures/0CC69994SP633913N","rel":"self","method":"GET","title":"GET"},{"href":"https://api.sandbox.paypal.com/v2/payments/captures/0CC69994SP633913N/refund","rel":"refund","method":"POST","title":"POST"},{"href":"https://api.sandbox.paypal.com/v2/checkout/orders/48N5803021263831D","rel":"up","method":"GET","title":"GET"}]}]}}],"links":[{"href":"https://api.sandbox.paypal.com/v2/checkout/orders/48N5803021263831D","rel":"self","method":"GET","title":"GET"}]},"referer":"https://kinggems.us/test/paypal.html"}
 */

// Payer information
$payer = ArrayHelper::getValue($data, 'payer', []);
$payer_email_address = ArrayHelper::getValue($payer, 'email_address');
$payer_id = ArrayHelper::getValue($payer, 'payer_id');
$payer_name = ArrayHelper::getValue($payer, 'name', []);
$payer_name = implode(" ", $payer_name);

// purchase information
$purchase_units = ArrayHelper::getValue($data, 'purchase_units', []);
$purchase_unit = reset($purchase_units);

// $payee = ArrayHelper::getValue($purchase_unit, 'payee', []);
// $merchant_email_address = ArrayHelper::getValue($payee, 'email_address');
// $merchant_id = ArrayHelper::getValue($payee, 'merchant_id');

// payment information
$payments = ArrayHelper::getValue($purchase_unit, 'payments', []);
$captures = ArrayHelper::getValue($payments, 'captures', []);
$capture = reset($captures);
$captureId = ArrayHelper::getValue($capture, 'id');
$create_time = ArrayHelper::getValue($capture, 'create_time');
$amount = ArrayHelper::getValue($capture, 'amount', []);
$value = ArrayHelper::getValue($amount, 'value', 0);
$currency_code = ArrayHelper::getValue($amount, 'currency_code', 'USD');

?>
<p style="margin: 4px 0 10px;">Dear <?=$payer_name;?></p>
<p style="margin: 4px 0 10px;">Have a good day!</p>
<p style="margin: 4px 0 10px;">Firstly, thanks for choosing our service at www.kinggems.us! </p>
<p style="margin: 4px 0 10px;">You receive this email because you have just made a successful payment for your order on our website.</p>
<p style="margin: 4px 0 10px;">We would like to ask your confirmation for the payment thru paypal with details, below:</p>
<ul>
<li>Transaction ID: <?=$captureId;?></li>
<li>Amount: <?=sprintf("%s %s", $currency_code, $value);?></li>
<li>Date: <?=$create_time;?></li>
</ul>
<p style="margin: 4px 0 10px;">1. The transaction was authorized by the owner of paypal account: "<?=$payer_email_address;?>"</p>
<p style="margin: 4px 0 10px;">2. You completely understand and agree with Kinggems's Policy: The payment was made, will be not refundable and being cancelled with any reason. </p>
<p style="margin: 4px 0 10px;">By replying <strong style="color:red">CONFIRM</strong> , you agree with the said term, and will never send a request for refund the said transaction thru paypal in the future.</p>
<p style="margin: 4px 0 10px;">Ps: Please be awared of that  your order will not be processed until we receive your confirmation email. </p>
<p style="margin: 4px 0 10px;">Thanks for the cooperation to help us to create a proper shopping enviroment and prevent the scammers.</p>
<p style="margin: 4px 0 10px;">Regards,</p>
<p style="margin: 4px 0 10px;">From KingGems, Payment Team!</p>