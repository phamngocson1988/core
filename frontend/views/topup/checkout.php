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
<section class="topup-page">
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
              <img src="/images/logo-king-coins.png" alt="">
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
                    <td><?=$item->getLabel();?> - <b><?=number_format($cart->getTotalCoin());?> King Coins</b></td>
                    <td><?=number_format($item->getCoin());?> x <?=$item->quantity;?><?php if ($cart->getPromotionUnit()) echo sprintf("(+ %s)", number_format($cart->getPromotionUnit()));?></td>
                    <td>$<?=number_format($item->getPrice());?> x <?=$item->quantity;?></td>
                  </tr>
                </tbody>
              </table>
              <div class="game-totals">
                <div class="product-grand-total">
                  <div class="grand-line">
                    <span>Subtotal:</span><span>$<?=number_format($sub, 1);?></span>
                  </div>
                  <?php if ($cart->getPromotionMoney()) :?>
                  <div class="grand-line">
                    <span>Saving:</span><span>-$<?=$cart->getPromotionMoney();?></span>
                  </div>
                  <?php endif;?>
                  <div class="grand-line last-line">
                    <span>Grand Total:</span><span>$<?=number_format($total, 1);?></span>
                  </div>
                </div>
              </div>
              <div class="text-right">
                <?php $form = ActiveForm::begin(['action' => Url::to(['topup/purchase'])]); ?>
                <?=Html::hiddenInput('identifier', 'paypal');?>
                <?= Html::submitButton('Submit', ['class' => 'btn-product-detail-add-to-cart']) ?>
                <?php ActiveForm::end();?>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
