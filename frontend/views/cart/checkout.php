<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use common\components\helpers\FormatConverter;
$cart = Yii::$app->cart;
$item = $cart->getItem();
$cart->applyPromotion();
?>
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
                      <input type="radio" name="identifier" id="opt2" value="skrill" class="hidden paygate" />
                      <span class="label"></span>
                      <div class="t-img-wrap-logo-payment">
                          <img src="/images/skrill.png" class="paygate-logo" alt="">
                      </div>
                    </label>

                    <label for="opt4" class="t-flex-item-center t-choose-payment radio">
                        <input type="radio" name="identifier" id="opt4" checked value="paypal" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/paypal.png" class="paygate-logo" alt="">
                        </div>
                    </label>
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
                        <input type="radio" name="identifier" id="opt7" value="postal-savings-bank-of-china" class="hidden paygate" />
                        <span class="label"></span>
                        <div class="t-img-wrap-logo-payment">
                            <img src="/images/postal-savings-bank-of-china.png" class="paygate-logo" alt="">
                        </div>
                    </label>
                    
                    <div class="t-wrap-btn is-desktop">
                      <?= Html::submitButton('Payment', ['class' => 'btn-product-detail-add-to-cart', 'onClick' => 'showLoader()']) ?>
                    </div>
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
                      </div>
                    </div>
                    <div class="is-mobile">
                      <?= Html::submitButton('Payment', ['class' => 'btn-product-detail-add-to-cart', 'onClick' => 'showLoader()']) ?>
                    </div>
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
  $('.price[paygate=' + _c).show();
});
$('.paygate:checked').trigger('change');
JS;
$this->registerJs($script);
?>