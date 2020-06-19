<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
$user = Yii::$app->user->getIdentity();
?>
<div class="container my-5">
  <div class="d-flex multi-step justify-content-between align-items-center active_step2">
    <div class="flex-fill">
      <div class="num"><a href="#">01</a></div>
      <p>Place Order</p>
    </div>
    <div class="flex-fill">
      <div class="num"><a href="#">02</a></div>
      <p>Order Details</p>
    </div>
    <div class="flex-fill">
      <div class="num"><a href="#">03</a></div>
      <p>Payment</p>
    </div>
    <div class="flex-fill">
      <div class="num"><a href="#">04</a></div>
      <p>Completed</p>
    </div>
  </div>
</div>
<div class="container my-5 single-order">
  <?php $form = ActiveForm::begin();?>
  <div class="row">
    <div class="col-md-5 info">
      <p class="lead mb-2">Payment method</p>
      <hr/>
      <?= $form->field($checkoutForm, 'paygate', [
          'options' => ['class' => 'btn-group-toggle multi-choose multi-choose-payment d-flex flex-wrap', 'data-toggle' => 'buttons'],
        ])->widget(\website\widgets\PaygateRadioListInput::className(), [
          'items' => $checkoutForm->fetchPaygates(),
          'options' => ['tag' => false]
        ])->label(false);?>
    </div>
    <div class="col-md-7">
      <!-- CART SUMMARY -->
      <div class="card card-summary">
        <h5 class="card-header text-uppercase">Card summary</h5>
        <div class="card-body">
          <p class="card-text text-red font-weight-bold">Game: <?=$model->title;?></p>
          <p class="text-green card-text font-weight-bold"><?=sprintf("%s %s", number_format($model->getTotalUnit()), strtoupper($model->getUnitName()));?></p>
          <p class="card-text">Version Global</p>
          <h5 class="card-title">Price Details</h5>
          <hr />
          <div class="d-flex">
            <div class="flex-fill w-100">Price</div>
            <div class="flex-fill w-100 text-right">$<?=number_format($model->getTotalPrice(), 1);?></div>
          </div>
          <div class="d-flex">
            <div class="flex-fill w-100 text-danger">Discount</div>
            <div class="flex-fill w-100 text-danger text-right">$0.0</div>
          </div>
          <hr />
          <div class="d-flex mb-3">
            <div class="flex-fill text-red font-weight-bold w-100">Total</div>
            <div class="flex-fill text-red font-weight-bold w-100 text-right">$<?=number_format($model->getTotalPrice(), 1);?>.0</div>
          </div>
          <button type="submit" class="btn btn-block btn-payment text-uppercase">Payment</button>
        </div>
      </div>
      <!-- END SUMMARY -->
    </div>
  </div>
  <?php ActiveForm::end();?>
</div>