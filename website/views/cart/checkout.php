<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\StringHelper;
$user = Yii::$app->user->getIdentity();
?>
<style type="text/css">
.blinking {
  /* margin: 20% 45% 0; */
  display:inline-block;
  padding: 5px;
  
  border: 4px solid #fe0000;
  animation-name: blinking;
  animation-duration: 1s;
  animation-iteration-count: 100;
}
@keyframes blinking {
  50% {
    border-color: #febb00;
  }
}
</style>
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
          <p class="text-green card-text font-weight-bold"><?=sprintf("%s %s", StringHelper::numberFormat($model->getTotalUnit(), 2), strtoupper($model->getUnitName()));?></p>
          <p class="card-text">Version Global</p>
          <h5 class="card-title">Price Details</h5>
          <hr />
          <div class="d-flex">
            <div class="flex-fill w-100">Price</div>
            <div class="flex-fill w-100 text-right" id="subTotal">$<?=StringHelper::numberFormat($model->getSubTotalPrice(), 2);?></div>
          </div>
          <?php if ($model->getPromotionDiscount()) : ?>
          <div class="d-flex">
            <div class="flex-fill w-100">Discount</div>
            <div class="flex-fill w-100 text-right">(<span id="promotionDiscount">$<?=StringHelper::numberFormat($model->getPromotionDiscount(), 2);?></span>)</div>
          </div>
          <?php endif;?>
          <div class="d-flex">
            <div class="flex-fill w-100">Transfer fee</div>
            <div class="flex-fill w-100 text-right" id="fee">$0</div>
          </div>
          <hr />
          <div class="d-flex mb-3" <?php if ($isOtherCurrency) : ?> style="margin-bottom: 0!important" <?php endif;?> >
            <div class="flex-fill text-red font-weight-bold w-100">Total</div>
            <div class="flex-fill text-red font-weight-bold w-100 text-right" id="total">$<?=StringHelper::numberFormat($model->getTotalPrice(), 2);?></div>
          </div>
          <?php if ($isOtherCurrency) : ?>
          <div class="d-flex mb-3">
            <div class="flex-fill text-red font-weight-bold w-100 text-right" id="otherCurrency">(<?=$otherCurrency;?>)</div>
          </div>
          <?php endif;?>

          <button type="submit" id="checkout-button" class="btn btn-block btn-payment text-uppercase">Payment</button>
        </div>
      </div>
      <!-- END SUMMARY -->
    </div>
  </div>
  <?php ActiveForm::end();?>
  <center class="mt-5 font-weight-bold" style="color: #180774; font-size: 1.2rem">Did you know about "Sub-payment"- An ultimated method for your business, <a href="https://kinggems.us/news/6-guidance-to-use-sub-payment-for-reseller-2021.html" class="blinking">Click here for more information</a></center>
</div>
<?php
$script = <<< JS
$('#checkout-button').on('click', function(e) {
  if ($(this).hasClass('inprogress')) return false;
  $(this).addClass('inprogress');
});

function Calculator() {
  // Elements
  var paygateElement = $( "input:checked" );
  // Calculate
  var paygate = paygateElement.val();

  $.ajax({
      url: '/cart/calculate-cart.html',
      type: "POST",
      dataType: 'json',
      data: {
        paygate: paygate,
      },
      success: function (result, textStatus, jqXHR) {
          console.log('Calculator', result);
          if (result.status) {
            ShowSummary(result.data)
          } else {
            toastr.error(result.errors.join('<br/>')); 
          }
      },
  });
};

function ShowSummary(data) {
  $('#subTotal').html(data.subTotalPayment);
  $('#promotionDiscount').html(data.promotionDiscount);
  $('#total').html(data.totalPayment);
  $('#fee').html(data.transferFee);
  if (data.isOtherCurrency) {
    $('#otherCurrency').show();
    $('#otherCurrency').html('('+data.otherCurrency+')');
  } else {
    $('#otherCurrency').hide();
  }
}

$( "input:radio" ).on('change', function(e) {
  console.log('radio click');
  Calculator();
});
JS;
$this->registerJs($script);
?>