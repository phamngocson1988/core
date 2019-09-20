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
              <div class="ck-tab-cart">
                <span class="done"><i class="fa fa-check"></i></span><span>Cart</span>
              </div>
              <div class="ck-tab-payment-confirm bg-white">
                <span class="done"><i class="fa fa-check"></i></span><span>Payment Confirm</span>
              </div>
              <div class="ck-tab-payment-method active">
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
                    <div class="t-flex-item-center t-use-kcoins">
                      <input type="radio" name="identifier" value="kinggems" <?=(!$can_place_order) ? 'disabled="true"' : "";?>  class="paygate">
                      <img src="/images/logo-kcoins-sm.png" alt="">
                      <div class="t-text-right">
                        Balance - <span class="t-red-bold"><?=number_format($balance);?></span> Kcoins
                        <span class="t-need-topup">Need to top up?
                        <a href="<?=Url::to(['topup/index']);?>">Click here</a>
                        </span>
                      </div>
                    </div>
                    <div class="t-flex-item-center t-choose-payment">
                      <input type="radio" name="identifier" value="skrill" class="paygate">
                      <img src="/images/skrill.png" alt="">
                    </div>
                    <div class="t-flex-item-center t-choose-payment">
                      <input type="radio" name="identifier" value="paypal" checked="" class="paygate">
                      <img src="/images/paypal.png" alt="">
                    </div>
                    <div class="t-flex-item-center t-choose-payment">
                      <input type="radio" name="identifier" value="alipay" class="paygate">
                      <img src="/images/alipay.png" alt="">
                    </div>
                    <div class="t-flex-item-center t-choose-payment">
                      <input type="radio" name="identifier" value="wechat" class="paygate">
                      <img src="/images/we.png" alt="">
                    </div>
                    <div class="is-desktop">
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
$('.paygate').on('click', function(){
  var _c = $(this).attr('value');
  $('.price').hide();
  $('.price[paygate=' + _c).show();
});
$('.paygate:checked').trigger('click');
JS;
$this->registerJs($script);
?>