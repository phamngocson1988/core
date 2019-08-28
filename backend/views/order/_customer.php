<?php
// if (!Yii::$app->user->can('view_customer')) return '';
if (!Yii::$app->user->can('saler')) return '';
?>
<div class="portlet blue-hoki box">
  <div class="portlet-title">
    <div class="caption">
      <i class="fa fa-cogs"></i>Buyer info
    </div>
  </div>
  <?php $customer = $order->customer;?>
  <div class="portlet-body">
    <div class="row static-info">
      <div class="col-md-5"> Customer: </div>
      <div class="col-md-7"> <?=$customer->name;?> </div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Email: </div>
      <div class="col-md-7"> <?=$customer->email;?> </div>
    </div>
    <div class="row static-info">
      <div class="col-md-5"> Phone Number: </div>
      <div class="col-md-7"> <?=sprintf("(%s)%s", $customer->country_code, $customer->phone);?> </div>
    </div>
  </div>
</div>