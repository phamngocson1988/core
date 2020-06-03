<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Register Affiliate';
?>

<div class="affiliate">
  <div class="hero-area">
    <div class="intro text-center">
      <h2 class="display-3">Become An Affiliate</h2>
      <p class="lead mb-3">Join an affiliate network for easy money – passive income</p>
      <span class="mb-5 text-hight-light">GET 20% COMMISSION ON EACH SALES</span>
      <div class="text-center">
        <a id="btn-bap" href="#" class="btn text-uppercase">BECOME A PARTNER</a>
      </div>
    </div>
  </div>

  <div class="container my-5">
    <div class="row feature">
      <div class="col-md-4 feature-item text-center">
        <img class="feature-image" src="https://image.flaticon.com/icons/svg/2885/2885427.svg" alt="">
        <h4 class="feature-title mt-4">Social Promote</h4>
        <p>Usually have Promos, Marketing Campaign on Social channel for brand building</p>
      </div>
      <div class="col-md-4 feature-item text-center">
        <img class="feature-image" src="https://image.flaticon.com/icons/svg/2885/2885620.svg" alt="">
        <h4 class="feature-title mt-4">Global Network</h4>
        <p>Network with 150+ Resellers worldwide & still counting</p>
      </div>
      <div class="col-md-4 feature-item text-center">
        <img class="feature-image" src="https://image.flaticon.com/icons/svg/2885/2885434.svg" alt="">
        <h4 class="feature-title mt-4">Great Support</h4>
        <p>Great Customer Service give the best customer experiences when visit our website</p>
      </div>
    </div>
  </div>

  <div class="spysection py-5">
    <div class="media mb-5">
      <img class="align-self-center mr-5"  src="https://image.flaticon.com/icons/svg/2641/2641491.svg" alt="">
      <div class="media-body align-self-center">
        <h5 class="mt-0">1. Register Your Account</h5>
        <p class="lead">Thousands of active customers using KingGems Top-up Service.</p>
      </div>
    </div>
    <div class="media mb-5">
      <div class="media-body align-self-center">
        <h5 class="mt-0">2. Share Your Links</h5>
        <p class="lead">Thousands of active customers using KingGems Top-up Service.</p>
      </div>
      <img class="align-self-center ml-5"  src="https://image.flaticon.com/icons/svg/2641/2641501.svg" alt="">
    </div>
    <div class="media mb-5 text-center">
      <div class="media-body align-self-center">
        <h5 class="mt-0">3. Enjoy Earnings</h5>
        <p class="lead w-50 m-auto">Thousands of active customers using KingGems Top-up Service.</p>
        <img class="align-self-center"  src="https://image.flaticon.com/icons/svg/2641/2641386.svg" alt="">
      </div>
    </div>
  </div>
  <div class="clients py-5">
    <div class="container text-center">
      <h3 class="heading">What our awesome clients said about us</h3>
    
      <div class="clients-slider py-5">
        <div class="item">
          <p class="lead w-100">@drfly109</p>
          <p class="mb-3">“At first I thought it was a scam. No way seller will sell something so valuable for so cheap. But it indeed was like that. Awesome!”</p>
          <div class="starts text-center">
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
          </div>
        </div>
        <div class="item">
          <p class="lead w-100">@theoampl</p>
          <p class="mb-3">“Cheapest fastest and 100% safe topup. Highly recommended”</p>
          <div class="starts text-center mt-auto">
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
          </div>
          
        </div>
        <div class="item">
          <p class="lead w-100">@ashstark</p>
          <p class="mb-3">“At first i was a bit skeptical. Not sure whether this is a scam. I give it go and BAM!!! Got the CP credits. Im so happy!!! Y’all can trust this seller. Fast & cheap. I definitely will buy again from this seller”</p>
          <div class="starts text-center mt-auto">
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
            <img class="icon-sm d-inline-block" src="/images/icon/star-active.svg"/>
          </div>
        </div>
        
      </div>
    </div>
  </div>

  <?php if ($sentRequest) : ?>
  <div id="bap-form" class="sign-up py-5">
    <div class="container text-center">
      <h3 class="heading">You have sent a request to Kinggems</h3>
      <form>
        <div class="text-center mt-5">
          <a role="button" href="<?=Url::to(['affiliate/cancel']);?>" class="btn btn-lg">Cancel Request</a>
        </div>
      </form>
      </div>
    </div>
  </div>
  <?php else : ?>
  <div id="bap-form" class="sign-up py-5">
    <div class="container text-center">
      <h3 class="heading">Sign up and start accepting payments</h3>
      <?php $form = ActiveForm::begin();?>
        <?= $form->field($model, 'preferred_im')->dropDownList($model->fetchPreferImList(), ['prompt' => 'Select Preferred IM'])->label('Preferred IM') ?>
        <?= $form->field($model, 'im_account')->textInput()->label('IM account') ?>
        <?= $form->field($model, 'company')->textInput()->label('Company Name') ?>
        <?= $form->field($model, 'channel')->textInput()->label('Channel') ?>
        <?= $form->field($model, 'channel_type')->dropDownList($model->fetchChannelTypeList(), ['prompt' => 'Select channel type'])->label('Channel type') ?>
        <div class="text-center mt-5">
          <button type="submit" class="btn btn-lg">Become a partner</button>
        </div>
      <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
  <?php endif; ?>
