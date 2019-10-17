<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
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
              <div><strong>Order Code:</strong></div>
              <div><span class="order-code-label"><?=$trn->id;?></span></div>
              <div>
                You can review <a class="review-order-btn" href="<?=Url::to(['user/transaction']);?>">Your Order</a>
              </div>
              <div class="order-info-email-note">
                <p>Details of the order have been sent to <?=$user->email;?></p>
                <p>If not found please check in Spam or Junk Folder mailbox.</p>
              </div>
              <?php if ($trn->payment_type == 'offline') : ?>
              <div class="order-info-email-note offline-success">
                <div class="row">
                  <div class="col-md-6">
                    <?php $data = $gateway->loadConfig();?>
                    <?php $logo = ArrayHelper::remove($data, 'logo');?>
                    <?php $content = ArrayHelper::remove($data, 'content');?>
                    <?=$content;?>
                    <p>Note: <?=$trn->remark;?></p>
                  </div>
                  <div class="col-md-6 paygate-logo">
                    <img src="<?=$logo;?>" width="150">
                  </div>
                </div>
                
              </div>
              <?php endif;?>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>