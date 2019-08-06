<?php
use yii\helpers\Url;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              THANK YOU FOR BUYING AT KINGGEMS.US!
            </div>
          </div>
          <div class="top-up-confirm">
            <div class="kingcoins-logo">
              <img src="/images/logo-king-coins.png" alt="">
            </div>
            <div class="cart-table order-final-info">
              <div>
                You can review <a class="review-order-btn" href="<?=Url::to(['user/transaction']);?>">Your Order</a>
              </div>
              <div class="order-info-email-note">
                <p>Details of the order have been sent to <?=Yii::$app->user->email;?></p>
                <p>If not found please check in Spam or Junk Folder mailbox.</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>