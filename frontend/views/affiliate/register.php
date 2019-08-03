<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use frontend\models\UserAffiliate;
use yii\captcha\Captcha;

$this->title = 'Register Affiliate';
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
            <?php if (!$sent) : ?>
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['autocomplete' => 'off']]); ?>
              <?= $form->field($model, 'preferred_im')->dropDownList(UserAffiliate::preferImList(), ['prompt' => 'Select Preferred IM'])->label('Preferred IM <span class="required">*</span>') ?>
              <?= $form->field($model, 'im_account')->textInput()->label('IM account <span class="required">*</span>') ?>
              <?= $form->field($model, 'company')->textInput()->label('Company Name <span class="required">*</span>') ?>
              <?= $form->field($model, 'channel')->textInput()->label('Channel <span class="required">*</span>') ?>
              <?= $form->field($model, 'channel_type')->dropDownList(UserAffiliate::channelTypeList(), ['prompt' => 'Select channel type'])->label('Channel type <span class="required">*</span>') ?>
               <div class="terms">
                  <div class="terms-row">
                      <input type="checkbox" id="agree"><span>* I have read and agree with the <font style="color: #ff3600;">Terms & Conditions.</font></span>
                  </div>
              </div>
              <div class="register-action">
                <button type="submit" class="cus-btn yellow has-shadow">Register Now!</button>
              </div>
            <?php ActiveForm::end(); ?>
            <?php else : ?>
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['autocomplete' => 'off']]); ?>
              <div class="register-action">
                <a href="<?=Url::to(['affiliate/cancel-request']);?>" role="button" class="cus-btn yellow has-shadow f20" id="cancel-request">CANCEL REQUEST</a>
              </div>
            <?php ActiveForm::end(); ?>
            <?php endif; ?>
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


<?php
$script = <<< JS
$("#cancel-request").ajax_action({
  confirm: true,
  confirm_text: 'Do you really want cancel this request?',
  callback: function(eletement, data) {
    location.reload();
  }
});
$('#form-signup').on('submit', function(e){
  if (!$('#agree').is(':checked')) {
    alert('You need to agree with our terms & conditions.');
    return false;
  }
});
JS;
$this->registerJs($script);
?>