<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\Pjax;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;
use frontend\components\cart\CartItem;
use frontend\components\cart\Cart;

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
              <div class="ck-tab ck-tab-payment-confirm active">
                <span>2</span><span>Payment Confirm</span>
              </div>
              <div class="ck-tab ck-tab-payment-method">
                <span>3</span><span>Payment Methods</span>
              </div>
            </div>
            <div class="checkout-tabs-content">
              <div class="ck-tab-content active" id="ck-cart-box">
                <?php $form = ActiveForm::begin(['id' => 'update-cart1']); ?>
                <?=Html::hiddenInput('scenario', CartItem::SCENARIO_RECEPTION_CART);?>
                <div class="game-confirm">
                  <div class="ck-confirm-note">
                    <span class="note-ico">!</span><span>Your information is committed to security by <span style="color:#ff3600">Kinggems.us!</span></span>
                  </div>
                  <?= $form->field($item, 'reception_email')->textInput();?>
                  <div class="t-wrap-btn is-desktop">
                    <?= Html::submitButton('Next', ['class' => 'btn-product-detail-add-to-cart', 'id' => 'update-cart-button']) ?>
                  </div>
                </div>
                <div class="checkout-cart-total">
                  <div class="game-name">
                    <div class="game-image">
                      <img src="<?=$item->getImageUrl('300x300');?>" alt="">
                    </div>
                    <h2><?=$item->getLabel();?></h2>
                  </div>
                  <div class="game-totals">
                    <div class="product-grand-total">
                      <div class="grand-line">
                        <span>Subtotal Unit:</span><span><?=number_format($cart->getSubTotalUnit());?></span>
                      </div>
                      <?php if ($cart->hasPromotion()) : ?>
                      <div class="grand-line">
                        <span>Promo:</span><span>+<?=$cart->getPromotionUnit();?></span>
                      </div>
                      <?php endif;?>
                      <div class="grand-line">
                        <span>Total Unit:</span><span><?=number_format($item->getTotalUnit());?></span>
                      </div>
                      <div class="grand-line last-line">
                        <span>Total Price:</span><span>$<?=number_format($item->getTotalPrice(), 1);?></span>
                      </div>
                    </div>
                  </div>
                  <div class="is-mobile">
                    <?= Html::submitButton('Next', ['class' => 'btn-product-detail-add-to-cart', 'id' => 'update-cart-button']) ?>
                  </div>
                </div>
                <?php ActiveForm::end(); ?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>