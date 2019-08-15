<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\User;
use yii\captcha\Captcha;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

$rangeDates = range(1, 31);
$dates = array_combine($rangeDates, $rangeDates);
$months = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12];
$rangeYears = array_reverse(range(date('Y') - 100, date('Y') - 18));
$years = array_combine($rangeYears, $rangeYears);
?>

<section class="page-title" id="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center pad-bot">
          <img src="/images/text-register.png" alt="">
          <p>Create your account</p>
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
              <p>Please note that we do not permit members to own more than (1) account.</p>
              <?= $form->field($model, 'firstname')->textInput()->label('Firstname <span class="required">*</span>') ?>
              <?= $form->field($model, 'lastname')->textInput()->label('Lastname <span class="required">*</span>') ?>
              <?= $form->field($model, 'username')->textInput()->label('Username <span class="required">*</span>') ?>
              <?= $form->field($model, 'password')->passwordInput()->label('Password <span class="required">*</span>') ?>
              <?= $form->field($model, 'repassword')->passwordInput()->label('Confirm Password <span class="required">*</span>') ?>
              <?= $form->field($model, 'email')->textInput()->label('Email <span class="required">*</span>') ?>
              <div class="form-group">
                <label>Date of Birth <span class="required">*</span></label>
                <?= $form->field($model, 'birth_date', [
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control date-day', 'id' => 'birth_date'],
                  'template' => '{input}'
                ])->dropDownList($dates);?>
                <?= $form->field($model, 'birth_month', [
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control date-month', 'id' => 'birth_month'],
                  'template' => '{input}'
                ])->dropDownList($months);?>
                <?= $form->field($model, 'birth_year', [
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control date-year', 'id' => 'birth_year'],
                  'template' => '{input}'
                ])->dropDownList($years);?>
              </div>
              
              <div class="form-group">
                <label>Contact Number <span class="required">*</span></label>
                <?= $form->field($model, 'country_code', [
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control phone-code', 'id' => 'country_code'],
                  'template' => '{input}'
                ])->dropDownList(Yii::$app->params['country_code']) ?>
                <?= $form->field($model, 'phone', [
                  'options' => ['tag' => false],
                  'inputOptions' => ['class' => 'form-control phone-number', 'id' => 'phone'],
                  'template' => '{input}'
                ])->textInput() ?>
              </div>
              
              <?= $form->field($model, 'captcha', [
                'inputOptions' => ['class' => 'form-control captcha-code', 'autocomplete' => false]
              ])->widget(Captcha::className(), [
                'template' => '{input}<div class="captcha-image">{image}</div>',
              ])->label('Validation Code <span class="required">*</span>') ?>

              <p>By clicking <b>Register Now!</b>, Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim </p>
               <div class="terms">
                  <div class="terms-row">
                      <input type="checkbox"><span>I would like to receive details of special offers, free bets and other promotions.</span>
                  </div>
                  <div class="terms-row">
                      <input type="checkbox" id="agree"><span>* I am at least 18 years of age an I accept the <font style="color: #ff3600;"><a href="<?=Url::to(['site/term', 'slug' => 'member']);?>" target="_blank">Terms & Conditions.</a></font></span>
                  </div>
              </div>
              <div class="register-action">
                <button type="submit" class="cus-btn yellow has-shadow">Register Now!</button>
                <div class="reg-login-now"><a href="<?=Url::to(['site/login', '#' => 'page-title']);?>">Have account. Login now</a></div>
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


<?php
$script = <<< JS
$('#birth_year, #birth_month, #birth_date').on('change', function() {
  correctDate($('#birth_year'),$('#birth_month'),$('#birth_date'));
  console.log('date trigger');
});
$('#country_code').on('change', function(){
  $('#phone').val($(this).val());
});
if (!$('#phone').val()) {
  $('#country_code').trigger('change');
}
$('#email').on('blur', function(){
  var _c = $(this).closest('.form-wrap');
  var _e = _c.find('.form-validation');
  $.ajax({
    url: '###LINK###',
    type: 'GET',
    dataType : 'json',
    data: {email: $(this).val()},
    success: function (result, textStatus, jqXHR) {
      if (result.status == true) { 
        _c.addClass('has-error');
        _e.html('This email address has already been taken.');
        $('#email').attr('aria-invalid', true);
        return false;
      }
    },
  });
});
var is_submit = false;
$('#form-signup').on('submit', function(e){
  if (!$('#agree').is(':checked')) {
    alert('You need to agree with our terms & conditions.');
    is_submit = false;
    return false;
  }
  if (is_submit) return false;
  is_submit = true;
});
JS;
$checkEmail = Url::to(['site/find-email']);
$script = str_replace('###LINK###', $checkEmail, $script);
$this->registerJs($script);
?>