<?php
use yii\helpers\Url;
use yii\widgets\LinkPager;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
$user = Yii::$app->user->identity;
?>

<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center pad-bot">
          <div class="page-title-image">
            <img src="/images/text-affiliate.png" alt="">
          </div>
          <p class="no-upper">Link & Earn</p>
          <p class="small-txt">Earn up to <span>20%</span> of Kinggems Net Profit....</p>
        </div>
      </div>
    </div>
  </div>
</section>
<section class="topup-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="affiliate-top">
            <div class="affiliate-terms">
              <h3>This will help you earn more profit. Start now!</h3>
              <p>By creating a reflink, you are indicating that you have read and agreed to the <a style="color: #ff3600;" href="#">Terms of Service.</a></p>
            </div>
            <div class="affiliate-create-reflink">
              <?php if (!$user->affiliate_request) : ?>
              <a href="<?=Url::to(['affiliate/send-request']);?>" role="button" class="cus-btn yellow has-shadow f20" id="send-request">REQUEST TO BE AFFILIATE</a>
              <?php else : ?>
              <a href="<?=Url::to(['affiliate/cancel-request']);?>" role="button" class="cus-btn yellow has-shadow f20" id="cancel-request">CANCEL REQUEST</a>
              <?php endif; ?>
            </div>
            <div class="has-left-border has-shadow">
              Your Members: 0
              <a href="<?=Url::to(['site/affiliate']);?>" style="color: #ff3600;">Tell me how to earn more</a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>
<?php
$script = <<< JS
$("#send-request").ajax_action({
  confirm: true,
  confirm_text: 'Do you really want to become our affiliate?',
  callback: function(eletement, data) {
    location.reload();
  }
});
$("#cancel-request").ajax_action({
  confirm: true,
  confirm_text: 'Do you really want cancel this request?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>
