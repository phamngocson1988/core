<?php
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
$setting = Yii::$app->settings;
?>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top no-mar-bot">
            <div class="has-left-border has-shadow no-mar-top">
              THANK YOU FOR ORDERING AT KINGGEMS.US!
            </div>
          </div>
          <div class="top-up-confirm">
            <div class="kingcoins-logo">
              <img src="/images/logo-king-coins.png" alt="">
            </div>
            <div class="cart-table order-final-info">
              <div>Order Code:</div>
              <div><span class="order-code-label"><?=$order->id;?></span></div>
              <div>
                You can review <a class="review-order-btn" href="<?=Url::to(['user/detail', 'id' => $order->id]);?>">Your Order</a>
              </div>
              <div class="order-info-email-note">
                <p>Details of the order have been sent to <?=$order->customer_email;?></p>
                <p>If not found please check in Spam or Junk Folder mailbox.</p>
              </div>
              <?php if ($order->payment_type == 'offline') : ?>
              <div class="order-info-email-note offline-success">
                <div class="row">
                  <div class="col-md-6">
                    <?php $data = $gateway->loadConfig();?>
                    <?php $logo = ArrayHelper::getValue($data, 'logo');?>
                    <?php $content = ArrayHelper::getValue($data, 'content');?>
                    <?php $logo_width = ArrayHelper::getValue($data, 'logo_width', 150);?>
                    <?php $logo_height = ArrayHelper::getValue($data, 'logo_height', 150);?>
                    <?=$content;?>
                    <p>Note: "<?=$user->username;?>_<?=$order->id;?>"</h3></p>
                  </div>
                  <div class="col-md-6 paygate-logo">
                    <img src="<?=$logo;?>" width="<?=$logo_width;?>" height="<?=$logo_height;?>">
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