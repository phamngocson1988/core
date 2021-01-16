<?php
use yii\helpers\Html;
use yii\helpers\Url;
$this->title = $name;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              <?= Html::encode($this->title) ?>
            </div>
          </div>
          <div class="top-up-confirm">
            <div class="kingcoins-logo">
              <img src="/images/error.png" width="300" height="300" alt="">
            </div>
            <div class="cart-table order-final-info">
              <div class="order-info-email-note">
                <?= nl2br(Html::encode($message)) ?>
              </div>
              <div>Back to <a class="review-order-btn" href="/">home page</a></div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>