<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use common\components\helpers\FormatConverter;
use alexandernst\devicedetect\DeviceDetect;
$detector = new DeviceDetect();
$cart = Yii::$app->kingcoin;
$item = $cart->getItem();
$cart->applyPromotion();
$sub = $cart->getSubTotalPrice();
$total = $cart->getTotalPrice(); 
?>
<style>
.hide {
  display: none;
}
</style>
<section class="topup-page t-payment">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top">
            <div class="has-left-border has-shadow no-mar-top t-flex-between">
              <p>YOUR ORDER <span>(1 Product)</span></p>
              <a href="<?=Url::to(['topup/index']);?>" class="t-flex-item-center">
                <img src="/images/edit_icon.png" alt="" />
                <span>Edit</span>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6 topup-paygates">
              <?php ActiveForm::begin(['action' => Url::to(['topup/purchase'])]); ?>
                <div class="t-flex-item-center t-warning-text">
                  <img src="/images/warning-icon.png" alt="">
                  <p class="t-text-right">Your information is committed to security by
                    <strong>Kinggems.us!</strong>
                  </p>
                </div>

                <label for="opt2" class="t-flex-item-center t-choose-payment radio">
                    <input type="radio" name="identifier" checked id="opt2" value="skrill" class="hidden paygate" />
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

                <div class="t-wrap-btn">
                  <!-- <a class="btn-product-detail-add-to-cart" href="javascript:;">PAYMENT</a> -->
                  <?= Html::submitButton('PAYMENT', ['class' => 'btn-product-detail-add-to-cart', 'id' => 'paygate-button-container', 'onClick' => 'showLoader()']) ?>
                  <div id="paypal-button-container" class="hide" style="margin-top: 50px; width:50px; height: 30px"></div>	
                </div>
              <?php ActiveForm::end();?>
            </div>
            <div class="col-12 col-md-6 t-right-payment">
              <div class="t-flex-item-center">
                <img src="/images/logo-kcoins.png" alt="">
                <div class="t-info-kcoins">
                  <p><?=$item->getLabel();?> - <strong><?=number_format($item->getCoin());?> King Coins</strong></p>
                  <p>Quantity: <span class="t-red-bold"><?=$item->quantity;?></span></p>
                  <p>Total King Coins: <span class="t-red-bold"><?=number_format($item->getTotalCoin());?></span></p>
                </div>
              </div>
              <div class="t-flex-between t-sub-total price" paygate="paypal" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="paypal" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>
              <?php
              $paypalFee = number_format(Yii::$app->settings->get('PaypalSettingForm', 'fee') * $cart->getTotalPrice() / 100, 1);
              ?>
              <div class="t-flex-between t-sub-total price" paygate='paypal' style="display: none">
                <span>Payment fee:</span><span>$<?=number_format($paypalFee, 1);?></span>
              </div>
              <div class="t-flex-between t-sub-total price" paygate='paypal' style="display: none">
                <span>Total Amount:</span><span>$<?=number_format($cart->getTotalPrice() + $paypalFee, 1);?></span>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="alipay" style="display: none">
                <p>Subtotal:</p>
                <p>CNY<?=FormatConverter::convertCurrencyToCny($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="alipay" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">CNY<?=FormatConverter::convertCurrencyToCny($item->getTotalPrice());?></span></p>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="postal-savings-bank-of-china" style="display: none">
                <p>Subtotal:</p>
                <p>CNY<?=FormatConverter::convertCurrencyToCny($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="postal-savings-bank-of-china" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">CNY<?=FormatConverter::convertCurrencyToCny($item->getTotalPrice());?></span></p>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="wechat" style="display: none">
                <p>Subtotal:</p>
                <p>CNY<?=FormatConverter::convertCurrencyToCny($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="wechat" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">CNY<?=FormatConverter::convertCurrencyToCny($item->getTotalPrice());?></span></p>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="skrill" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="skrill" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="payoneer" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="payoneer" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="bitcoin" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="bitcoin" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>

              <div class="t-flex-between t-sub-total price" paygate="western_union" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="western_union" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>

              <!-- <div class="t-flex-between t-sub-total price" paygate="neteller" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="neteller" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div> -->

              <?php
              $standFee = number_format(Yii::$app->settings->get('StandardCharteredSettingForm', 'fee') * $cart->getTotalPrice() / 100, 1);
              ?>
              <div class="t-flex-between t-sub-total price" paygate="standard_chartered" style="display: none">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total price" paygate="standard_chartered" style="display: none">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>
              <div class="t-flex-between t-sub-total price" paygate='standard_chartered' style="display: none">
                <span>Payment fee:</span><span>$<?=number_format($standFee, 1);?></span>
              </div>
              <div class="t-flex-between t-sub-total price" paygate='standard_chartered' style="display: none">
                <span>Total Amount:</span><span>$<?=number_format($cart->getTotalPrice() + $standFee, 1);?></span>
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
  parent.find('input').prop('checked', true);
  parent.find('input').trigger('change');
});
$('.paygate').change(function(){
  var _c = $(this).attr('value');
  // Show/hide value
  $('.price').hide();
  $('.price[paygate=' + _c).show();
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
$paypalCapture = Url::to(['topup/paypal-capture']);
$paypalAmount = round($cart->getTotalPrice() + $paypalFee, 1);
$script = str_replace('###LINK###', $paypalCapture, $script);
$script = str_replace('###AMOUNT###', $paypalAmount, $script);
$this->registerJs($script);
?>