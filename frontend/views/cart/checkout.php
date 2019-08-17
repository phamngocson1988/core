<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
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
                  <div class="game-confirm">
                    <div class="ck-confirm-note">
                      <span class="note-ico">!</span><span>Your information is committed to security by <strong>Kinggems.us!</strong></span>
                    </div>
                    <div class="method-chooser">
                      <!-- <div class="method-line">
                        <input type="radio" name="abc" id=""><span>Visa/Master Card</span>
                      </div> -->
                      <div class="method-line">
                        <input type="radio" name="identifier" value="paypal" checked=""><span>Paypal</span>
                      </div>
                      <div class="method-line">
                        <input type="radio" name="identifier" value="kinggems" <?=(!$can_place_order) ? 'disabled="true"' : "";?> ><span>King Coins - Balance <?=(!$can_place_order) ? Html::a(' - Go to Topup', Url::to(['topup/index']), ['style' => 'color: #ff3600']) : '';?></span>
                      </div>
                      <div class="method-line">
                        <input type="radio" name="identifier" value="alipay"><span>Alipay</span>
                      </div>
                      <div class="method-line">
                        <input type="radio" name="identifier" value="wechat"><span>Wechat</span>
                      </div>
                      <!-- <div class="method-line">
                        <input type="radio" name="abc" id=""><span>Skrill</span>
                      </div> -->
                    </div>
                    <div class="is-desktop">
                      <?= Html::submitButton('Payment', ['class' => 'btn-product-detail-add-to-cart']) ?>
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
                        <div class="grand-line last-line">
                          <span>Total Price:</span><span>$<?=$cart->getTotalPrice();?></span>
                        </div>
                      </div>
                    </div>
                    <div class="is-mobile">
                      <?= Html::submitButton('Payment', ['class' => 'btn-product-detail-add-to-cart']) ?>
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