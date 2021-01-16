<?php 
use yii\helpers\Url;
?>
<div class="section-md text-center">
  <div class="container container-wide">
    <p class="heading-1 breadcrumbs-custom-title">THANK YOU</p>
    <p>FOR PURCHASING ORDER #<?=$order->id;?></p>
    <a class="button button-secondary button-nina" href="<?=$viewUrl;?>"> View Order </a>
  </div>
</div>
<?php
$script = <<< JS
gtag('event', 'purchase', {
  transaction_id: '$order->id',
  value: $order->total_price,
  currency: 'USD',
  tax: 0,
  shipping: 0,
  items: [{
    item_id: '$order->id',
    item_name: '$order->game_title',
    quantity: $order->quantity,
    price: $order->total_price,
  }]
});

window.location.href = '$viewUrl';
JS;
$this->registerJs($script);
?>