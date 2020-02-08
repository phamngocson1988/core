<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\FormatConverter;
use alexandernst\devicedetect\DeviceDetect;
$detector = new DeviceDetect();
$cart = Yii::$app->cart;
$item = $cart->getItem();
$cart->applyPromotion();
?>
<style>
.hide {
  display: none;
}
</style>
<section class="checkout-page">
  <div class="container">
    <div class="checkout-block">
      <div class="checkout-navigation">
        <div class="row">
          <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
            <div class="checkout-navigation-tabs has-shadow">
              <div class="ck-tab ck-tab-cart">
                <span class="done"><i class="fa fa-check"></i></span><span>Cart</span>
              </div>
              <div class="ck-tab ck-tab-payment-confirm">
                <span>2</span><span>Payment Confirm</span>
              </div>
              <div class="ck-tab ck-tab-payment-method active">
                <span>3</span><span>Payment Methods</span>
              </div>
            </div>
            <div class="checkout-tabs-content">
              <div class="ck-tab-content active" id="ck-cart-box">
                <?php $form = ActiveForm::begin(['id' => 'form-signup', 'action' => Url::to(['cart/purchase'])]); ?>
                  <div class="game-confirm t-payment">
                    <div class="t-flex-item-center t-warning-text">
                      <img src="/images/warning-icon.png" alt="">
                      <p class="t-text-right">Your information is committed to
                        security by
                        <strong>Kinggems.us!</strong>
                      </p>
                    </div>
                    
                    <div class="t-wrap-first-payment">
                      <label for="opt0" class="t-flex-item-center t-choose-payment radio t-first-payment">
                          <input type="radio" name="identifier" value="kinggems" <?=(!$can_place_order) ? 'disabled="true"' : "";?> id="opt0" class="hidden paygate" />
                          <span class="label"></span>
                          <img src="/images/logo-kcoins-sm.png" alt="" class="paygate-logo">
                      </label>
                      <div class="t-text-right">
                          Balance - <span class="t-red-bold"><?=number_format($balance);?></span> Kcoins
                          <span class="t-need-topup">Need to top up?
                              <a href="#">
                                  Click here
                              </a>
                          </span>
                      </div>
                    </div>
                    <label for="opt2" class="t-flex-item-center t-choose-payment radio">
                      <input type="radio" name="identifier" id="opt2" checked value="skrill" class="hidden paygate" />
                      <span class="label"></span>
                      <div class="t-img-wrap-logo-payment">
                          <img src="/images/skrill.png" class="paygate-logo" alt="">
                      </div>
                    </label>

                    <!-- <label for="opt4" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt4" checked value="paypal" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/paypal.png" class="paygate-logo" alt="">
                        </div>
                    </label> -->
                    <label for="opt5" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt5" value="alipay" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/alipay.png" class="paygate-logo" alt="">
                        </div>
                    </label>
                    <label for="opt6" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt6" value="wechat" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/we.png" class="paygate-logo" alt="">
                        </div>
                    </label>

                    <label for="opt7" class="t-flex-item-center t-choose-payment radio">
                      <input type="radio" name="identifier" id="opt7" value="bitcoin" class="hidden paygate" />
                      <span class="label"></span>
                      <div class="t-img-wrap-logo-payment">
                          <img src="/images/bitcoin.png" class="paygate-logo" alt="">
                      </div>
                    </label>
                    
                    <label for="opt8" class="t-flex-item-center t-choose-payment radio">
                      <input type="radio" name="identifier" id="opt8" value="payoneer" class="hidden paygate" />
                      <span class="label"></span>
                      <div class="t-img-wrap-logo-payment">
                          <img src="/images/payoneer.png" class="paygate-logo" alt="">
                      </div>
                    </label>

                    <label for="opt9" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt9" value="postal-savings-bank-of-china" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/postal-savings-bank-of-china.png" class="paygate-logo" alt="">
                        </div>
                    </label>

                    <label for="opt10" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt10" value="western_union" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/western_union.png" class="paygate-logo" alt="">
                        </div>
                    </label>

                    <!-- <label for="opt11" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt11" value="neteller" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/neteller.png" class="paygate-logo" alt="">
                        </div>
                    </label> -->

                     <label for="opt12" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt12" value="standard_chartered" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/standard_chartered.png" class="paygate-logo" alt="">
                        </div>
                    </label>

                    <?php if (!$detector->isMobile()): ?>
                    <div class="t-wrap-btn is-desktop">
                      <?= Html::submitButton('Payment', ['class' => 'btn-product-detail-add-to-cart', 'id' => 'paygate-button-container', 'onClick' => 'showLoader()']) ?>
                      <div id="paypal-button-container" class="hide" style="margin-top: 50px; width:50px; height: 30px"></div>  
                    </div>
                    <?php endif;?>
                  </div>
                  <div class="checkout-cart-total">
                    <div class="order-quantity">
                      Order <span>(1 product)</span>
                    </div>
                    <div class="game-name">
                      <div class="game-image">
                        <img src="<?=$item->getImageUrl('300x300');?>" alt="">
                      </div>
                      <h2><?=$item->getLabel();?></h2>
                    </div>
                    <div class="game-totals">
                      <div class="product-grand-total">
                        <div class="grand-line">
                          <span>Subtotal Unit:</span><span><?=$cart->getSubTotalUnit();?></span>
                        </div>
                        <?php if ($cart->hasPromotion()) : ?>
                        <div class="grand-line">
                          <span>Promo:</span><span>+<?=$cart->getPromotionUnit();?></span>
                        </div>
                        <?php endif;?>
                        <div class="grand-line">
                          <span>Total Unit:</span><span><?=$cart->getTotalUnit();?></span>
                        </div>

                        <div class="grand-line last-line price" paygate='paypal' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>
                        <?php
                        $fee = number_format(Yii::$app->settings->get('PaypalSettingForm', 'fee') * $cart->getTotalPrice() / 100, 1);
                        ?>
                        <div class="grand-line price" paygate='paypal' style="display: none">
                          <span>Payment fee:</span><span>$<?=number_format($fee, 1);?></span>
                        </div>
                        <div class="grand-line price" paygate='paypal' style="display: none">
                          <span>Total Amount:</span><span>$<?=number_format($cart->getTotalPrice() + $fee, 1);?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='kinggems' style="display: none">
                          <span>Total Price:</span><span><?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='alipay' style="display: none">
                          <span>Total Price:</span><span>CNY<?=FormatConverter::convertCurrencyToCny($cart->getTotalPrice());?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='postal-savings-bank-of-china' style="display: none">
                          <span>Total Price:</span><span>CNY<?=FormatConverter::convertCurrencyToCny($cart->getTotalPrice());?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='wechat' style="display: none">
                          <span>Total Price:</span><span>CNY<?=FormatConverter::convertCurrencyToCny($cart->getTotalPrice());?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='skrill' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='payoneer' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>
                        <div class="grand-line last-line price" paygate='bitcoin' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>

                        <div class="grand-line last-line price" paygate='western_union' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>

                        <!-- <div class="grand-line last-line price" paygate='neteller' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div> -->

                        <?php
                        $standFee = number_format(Yii::$app->settings->get('StandardCharteredSettingForm', 'fee') * $cart->getTotalPrice() / 100, 1);
                        ?>
                        <div class="grand-line last-line price" paygate='standard_chartered' style="display: none">
                          <span>Total Price:</span><span>$<?=number_format($cart->getTotalPrice(), 1);?></span>
                        </div>
                        <div class="grand-line price" paygate='standard_chartered' style="display: none">
                          <span>Payment fee:</span><span>$<?=number_format($standFee, 1);?></span>
                        </div>
                        <div class="grand-line price" paygate='standard_chartered' style="display: none">
                          <span>Total Amount:</span><span>$<?=number_format($cart->getTotalPrice() + $standFee, 1);?></span>
                        </div>
                      </div>
                    </div>
                    <?php if ($detector->isMobile()): ?>
                    <div class="is-mobile">
                      <?= Html::submitButton('Payment', ['class' => 'btn-product-detail-add-to-cart', 'id' => 'paygate-button-container', 'onClick' => 'showLoader()']) ?>
                      <div id="paypal-button-container" class="hide" style="margin-top: 50px; width:50px; height: 30px"></div> 
                    </div>
                    <?php endif;?>
                  </div>
                <?php ActiveForm::end();?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$('form').submit(function(){
    $('input[type=submit]', this).attr('disabled', 'disabled');
});
$('.paygate-logo').on('click', function(){
  var parent = $(this).closest('div');
  parent.find('input:not(:disabled)').prop('checked', true);
  parent.find('input').trigger('change');
});
$('.paygate').change(function(){
  var _c = $(this).attr('value');
  $('.price').hide();
  $('.price[paygate=' + _c + ']').show();

  // Show/hide payment button
  if (_c == 'paypal') {
    $('#paypal-button-container').removeClass('hide');
    $('#paygate-button-container').addClass('hide');
  } else {
    $('#paygate-button-container').removeClass('hide');
    $('#paypal-button-container').addClass('hide');
  }
});
$('.paygate:checked').trigger('change');

paypal.Buttons({
createOrder: function(data, actions) {
  return actions.order.create({
    purchase_units: [{
      amount: {
        value: '###AMOUNT###'
      }
    }]
  });
},
onApprove: function(data, actions) {
  return actions.order.capture().then(function(details) {
    if (details.status == "COMPLETED") {
      $.ajax({
        url: '###LINK###',
        type: 'POST',
        dataType : 'json',
        data: details,
        success: function (result, textStatus, jqXHR) {
          console.log(result);
          if (result['status']) {
            showLoader();
            window.location.href = result['success_link'];
          } else {
            swal("Payment fail.", "Transaction ID: " + result['transaction'], "warning");
          }
        },
      });
    } else {
      swal("Payment fail.", "Payment ID: " + details.id, "warning");
    }
    
  });
}
}).render('#paypal-button-container');
JS;
$paypalCapture = Url::to(['cart/paypal-capture']);
$paypalAmount = round($cart->getTotalPrice() + $fee, 1);
$script = str_replace('###LINK###', $paypalCapture, $script);
$script = str_replace('###AMOUNT###', $paypalAmount, $script);
$this->registerJs($script);
?>