
<?php
$paymentData = json_decode($order->payment_data, true);
$paymentLink = $paymentData['paygate_url'];
$orderId = $order->id;
$gameTitle = addslashes($order->game_title);
$quantity = $order->quantity;
$totalPrice = $order->total_price_by_currency;
$currency = $order->currency;
?>
<div class="section-md">
    <div class="container container-wide" style="padding-bottom: 40px; padding-top: 40px">
        <div class="col-md-7 mx-auto">
            <div class="card card-summary">
                <h5 class="card-header text-uppercase" style="color: #ff6129">THANK YOU</h5>
                <div class="card-body">
                    <div class="text-center">
                        <p>FOR PURCHASING ORDER <b>#<?= $orderId; ?></b></p>
                        <p>Your order will not be confirmed until you complete the payment, please click the link below
                            and complete the payment.</p>
                        <p><a class="btn" style="background: #ff6129; color: #fff" href="javascript:;" id="submit_webmoney_button">Click here to paid</a></p>
                        <a class="button button-secondary button-nina" href="<?= $viewUrl; ?>"> View Order </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<form method="POST" action="<?=$paymentLink;?>" accept-charset="utf-8" style="display:none" id="submit_webmoney_form">
    <input type="hidden" name="LMI_PAYMENT_AMOUNT" value="<?=$paymentData['LMI_PAYMENT_AMOUNT'];?>"/>
    <input type="hidden" name="LMI_PAYMENT_DESC" value="<?=$paymentData['LMI_PAYMENT_DESC'];?>"/>
    <input type="hidden" name="LMI_PAYMENT_NO" value="<?=$paymentData['LMI_PAYMENT_NO'];?>"/>
    <input type="hidden" name="LMI_PAYEE_PURSE" value="<?=$paymentData['LMI_PAYEE_PURSE'];?>"/>
    <input type="hidden" name="LMI_PAYMENTFORM_SIGN" value="<?=$paymentData['LMI_PAYMENTFORM_SIGN'];?>"/>
</form>
<?php
$script = <<< JS
gtag('event', 'purchase', {
  transaction_id: '$orderId',
  value: $totalPrice,
  currency: 'USD',
  tax: 0,
  shipping: 0,
  items: [{
    item_id: '$orderId',
    item_name: '$gameTitle',
    quantity: $quantity,
    price: $totalPrice,
  }]
});

$('#submit_webmoney_button').on('click', function() {
    document.getElementById("submit_webmoney_form").submit();
});
JS;
$this->registerJs($script);
?>
