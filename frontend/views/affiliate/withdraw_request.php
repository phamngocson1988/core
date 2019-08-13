<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\models\UserAffiliate;
use yii\captcha\Captcha;

$this->title = 'Request withdraw commission';
$this->params['breadcrumbs'][] = $this->title;
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
<section class="register-page">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-12 col-lg-7 col-md-7 col-sm-12">
          <div class="register-block">
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['autocomplete' => 'off']]); ?>
              <div class="form-group">
                <label class="control-label">Available amount</label>
                <label class="control-label">$<?=$user->getAvailabelCommission();?></label>
              </div>
              <?= $form->field($model, 'amount', ['inputOptions' => ['type' => 'number']])->textInput()->label('Amount you wish to withdraw <span class="required">*</span>') ?>
              <div class="register-action">
                <a href="<?=Url::to(['affiliate/withdraw']);?>" class="cus-btn has-shadow" style="background-color: #ccc">Back</a>
                <button type="submit" class="cus-btn yellow has-shadow">Sent request!</button>
              </div>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
        <div class="col col-12 col-lg-1 col-md-1 col-sm-12"></div>
        <div class="col col-12 col-lg-4 col-md-4 col-sm-12">
          <div class="reg-deposit">
            <div class="has-left-border has-shadow">
              <img src="/images/ico-deposit-large.png" alt="">
              <p class="large-txt">
                Deposit
              </p>
              <p class="small-txt">Fast, Safe and Secure!</p>
            </div>
          </div>
          <div class="reg-useful-tools">
            <h3>Useful Tools</h3>
            <div class="has-left-border gray has-shadow">
              <img src="/images/ico-how-to-deposit.png" alt="">
              <p class="small-txt">How to</p>
              <p class="large-txt">Deposit</p>
            </div>
            <div class="has-left-border gray has-shadow">
              <img src="/images/ico-how-to-transfer.png" alt="">
              <p class="small-txt">How to</p>
              <p class="large-txt">Transfer</p>
            </div>
            <div class="has-left-border gray has-shadow">
              <img src="/images/ico-how-to-play.png" alt="">
              <p class="small-txt">How to</p>
              <p class="large-txt">Play</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>