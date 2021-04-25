<?php 
use yii\helpers\Url;
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
                        <a class="button button-secondary button-nina" href="<?= $viewUrl; ?>"> View Order </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
$script = <<< JS
gtag('event', 'purchase', {
  transaction_id: '$orderId',
  value: $totalPrice,
  currency: '$currency',
  tax: 0,
  shipping: 0,
  items: [{
    item_id: '$orderId',
    item_name: '$gameTitle',
    quantity: $quantity,
    price: $totalPrice,
  }]
});

window.location.href = '$viewUrl';
JS;
$this->registerJs($script);
?>