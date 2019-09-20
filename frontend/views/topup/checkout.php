<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$cart = Yii::$app->kingcoin;
$item = $cart->getItem();
$cart->applyPromotion();
$sub = $cart->getSubTotalPrice();
$total = $cart->getTotalPrice(); 
?>
<section class="topup-page t-payment">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top t-flex-between">
              <p>YOUR ORDER <span>(1 Product)</span></p>
              <a href="<?=Url::to(['topup/index']);?>" class="t-flex-item-center">
                <img src="/images/edit_icon.png" alt="" />
                <span>Edit</span>
              </a>
            </div>
          </div>
          <div class="row">
            <div class="col-12 col-md-6">
              <?php ActiveForm::begin(['action' => Url::to(['topup/purchase'])]); ?>
                <div class="t-flex-item-center t-warning-text">
                  <img src="/images/warning-icon.png" alt="">
                  <p class="t-text-right">Your information is committed to security by
                    <strong>Kinggems.us!</strong>
                  </p>
                </div>
                <div class="t-flex-item-center t-choose-payment">
                  <input type="radio" name="identifier" value="paypal">
                  <img src="/images/paypal.png" alt="">
                </div>
                <div class="t-flex-item-center t-choose-payment">
                  <input type="radio" name="identifier" value="skrill">
                  <img src="/images/skrill.png" alt="">
                </div>
                <div class="t-flex-item-center t-choose-payment">
                  <input type="radio" name="identifier" value="payoneer">
                  <img src="/images/payoneer.png" alt="">
                </div>
                <div class="t-flex-item-center t-choose-payment">
                  <input type="radio" name="identifier" value="alipay">
                  <img src="/images/alipay.png" alt="">
                </div>
                <div class="t-flex-item-center t-choose-payment">
                  <input type="radio" name="identifier" value="wechat">
                  <img src="/images/we.png" alt="">
                </div>
                <div class="is-desktop">
                  <!-- <a class="btn-product-detail-add-to-cart" href="javascript:;">PAYMENT</a> -->
                  <?= Html::submitButton('PAYMENT', ['class' => 'btn-product-detail-add-to-cart', 'onClick' => 'showLoader()']) ?>
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
              <div class="t-flex-between t-sub-total" paygate="paypal">
                <p>Subtotal:</p>
                <p>$<?=number_format($item->getPrice() * $item->quantity);?></p>
              </div>
              <div class="t-flex-between t-payment-total" paygate="paypal">
                <p>Grand Total:</p>
                <p><span class="t-red-bold">$<?=number_format($item->getTotalPrice());?></span></p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- <section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              Your Order <span>(1 Product)</span>
              <div class="top-bar-action">
                <a class="edit-btn" href="<?=Url::to(['topup/index']);?>">Edit</a>
              </div>
            </div>
          </div>
          <div class="top-up-confirm">
            <div class="kingcoins-logo">
              <img src="//images/logo-king-coins.png" alt="">
            </div>
            <div class="cart-table">
              <table>
                <thead>
                  <tr>
                    <th>Pricing Package</th>
                    <th>King Coins</th>
                    <th>USD</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td><?=$item->getLabel();?> - <b><?=number_format($item->getCoin());?> King Coins</b></td>
                    <td><?=number_format($item->getCoin());?> x <?=$item->quantity;?></td>
                    <td>$<?=number_format($item->getPrice());?> x <?=$item->quantity;?></td>
                  </tr>
                </tbody>
              </table>
              <div class="game-totals">
                <div class="product-grand-total">
                  <?php if ($cart->getPromotionCoin()) : ?>
                  <div class="grand-line">
                    <span>Promotion coin:</span><span><?=number_format($cart->getPromotionCoin());?> King Coins</span>
                  </div>
                  <?php endif;?>
                  <div class="grand-line">
                    <span>Total coin:</span><span><?=number_format($cart->getTotalCoin());?> King Coins</span>
                  </div>
                  <div class="grand-line">
                    <span>Subtotal price:</span><span>$<?=number_format($sub, 1);?></span>
                  </div>
                  <?php if ($cart->getPromotionMoney()) :?>
                  <div class="grand-line">
                    <span>Saving:</span><span>-$<?=$cart->getPromotionMoney();?></span>
                  </div>
                  <?php endif;?>
                  <div class="grand-line last-line">
                    <span>Grand Total price:</span><span>$<?=number_format($total, 1);?></span>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <?php $form = ActiveForm::begin(['action' => Url::to(['topup/purchase'])]); ?>
                <?=Html::hiddenInput('identifier', 'paypal');?>
                <?= Html::submitButton('Submit', ['class' => 'btn-product-detail-add-to-cart', 'onClick' => 'showLoader()']) ?>
                <?php ActiveForm::end();?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section> -->
<?php
$script = <<< JS
$('form').submit(function(){
    $('input[type=submit]', this).attr('disabled', 'disabled');
});
JS;
$this->registerJs($script);
?>