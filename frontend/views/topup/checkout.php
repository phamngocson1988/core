<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\bootstrap\ActiveForm;

$cart = Yii::$app->kingcoin;
$item = $cart->getItem();
?>
<section class="section-xl text-center bg-default">
  <div class="container">
    <h3 class="text-gray-4">confirmation</h3>
  </div>
  <!-- Style switcher-->
  <div class="style-switcher" data-container="">
    <div class="style-switcher-container">
      <section class="section section-xl bg-default text-center bg-image section-2-columns">
        <div class="bg-image-poster"><img src="/images/bg-02.jpg" alt=""></div>
        <div class="container">
          <div class="row justify-content-sm-end row-30 row-fix">
            <!-- Pricing Box Lg -->
            <div class="col-md-8 col-xl-6">
              <div class="pricing-box pricing-box-lg pricing-box-novi">
                <div class="pricing-box-header text-sm-left">
                  <h4><?=$item->getLabel();?></h4>
                </div>
                <div class="pricing-box-body">
                  <div class="row row-fix align-items-sm-center">
                    <div class="col-sm-6 text-sm-left">
                      <ul class="pricing-box-list list-marked">
                        <li>Quantity: <?=$item->quantity;?></li>
                        <li>King Coins: <?=($item->getPricing()->num_of_coin * $item->quantity);?></li>
                      </ul>
                    </div>
                    <div class="col-sm-6 text-sm-right">
                      <div class="pricing-box-price">
                        <?php
                        $sub = $cart->getSubTotalPrice();
                        $total = $cart->getTotalPrice(); 
                        ?>
                        <?php if ($sub != $total) :?>
                        <div class="pricing-box-price-old">
                          <div class="heading-5">$<?=($sub);?></div>
                        </div>
                        <?php endif;?>
                        <div class="pricing-box-price-new">
                          <div class="heading-2">$<?=($total);?></div>
                        </div>
                      </div>
                      <?php $form = ActiveForm::begin([
                        'action' => Url::to(['pricing/purchase']),
                      ]); ?>
                      <?=Html::radioList ( 'identifier', 'paypal', ['paypal' => 'Paypal', 'skrill' => 'Skrill'] );?>
                      <?= Html::submitButton('Pay by Palpal', ['class' => 'button button-sm button-secondary button-nina', 'onclick' => 'showLoader()']) ?>
                      <?php ActiveForm::end();?>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </section>
    </div>
  </div>
</section>
