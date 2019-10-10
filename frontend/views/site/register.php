<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\User;
use yii\captcha\Captcha;
use frontend\forms\RegisterForm;

$this->title = 'Register';
$this->params['breadcrumbs'][] = $this->title;

$rangeDates = range(1, 31);
$dates = array_combine($rangeDates, $rangeDates);
$months = [1 => 1, 2 => 2, 3 => 3, 4 => 4, 5 => 5, 6 => 6, 7 => 7, 8 => 8, 9 => 9, 10 => 10, 11 => 11, 12 => 12];
$rangeYears = array_reverse(range(date('Y') - 100, date('Y') - 18));
$years = array_combine($rangeYears, $rangeYears);
?>
<style type="text/css">
  .register-section {display: none;}
  .register-section.show {display: block;}
</style>

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

<section class="register-page register-section <?php if ($scenario == RegisterForm::SCENARIO_VALIDATE) echo 'show';?>">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-12 col-lg-7 col-md-7 col-sm-12">
          <div class="register-block">
            <?php $form = ActiveForm::begin(['id' => 'form-signup', 'options' => ['autocomplete' => 'off']]); ?>
              <p>Please note that we do not permit members to own more than (1) account.</p>
                  <input type="hidden" value="<?=$scenario;?>" name="scenario"/>
              <?= $form->field($model, 'firstname', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->textInput()->label('Firstname <span class="required">*</span>') ?>
              
              <?= $form->field($model, 'lastname', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->textInput()->label('Lastname <span class="required">*</span>') ?>
              <?= $form->field($model, 'username', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->textInput()->label('Username <span class="required">*</span>') ?>
              <?= $form->field($model, 'password', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->passwordInput()->label('Password <span class="required">*</span>') ?>
              <?= $form->field($model, 'repassword', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->passwordInput()->label('Confirm Password <span class="required">*</span>') ?>
              <?= $form->field($model, 'email', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->textInput()->label('Email <span class="required">*</span>') ?>
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
                ])->dropDownList($model->listCountries(), ['options' => $model->listCountryAttributes()]) ?>
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

              <p>By clicking <b>Register Now!</b>, You agree accessing & using the Services, you acknowledge that you have read, understood and agreed to Kinggems's Conditions and Terms. </p>
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
          <?php echo $this->render('@frontend/views/site/_reg_deposit.php');?>
        </div>
      </div>
    </div>
  </div>
</section>

<section class="verify-code register-section <?php if ($scenario == RegisterForm::SCENARIO_INFORMATION) echo 'show';?>">
  <div class="container">
    <div class="small-container">
      <div class="row">
        <div class="col col-lg-12 col-md-12 col-sm-12 col-12">
          <div class="title has-left-border has-shadow">
            verify code
          </div>
          <div class="row wrap-code-verify">
            <div class="col-12 col-md-4 left-code">
              <img src="/images/img-code.png" alt="">
            </div>
            <div class="col-12 col-md-8 right-code">
              <p>Enter the 4 digit code we sent you<br>
                via SMS <span>(<?=substr_replace($model->phone, str_pad("", strlen($model->phone) - 5, "*"), 3, -2);?>)</span> to continue
              </p>
              <?php $form = ActiveForm::begin(['id' => 'verify-form', 'options' => ['autocomplete' => 'off']]); ?>
                <div class="wrap-numb">
                  <input type="hidden" value="<?=$scenario;?>" name="scenario"/>
                  <?= $form->field($model, 'digit_1', [
                    'options' => ['tag' => false],
                    'template' => '{input}',
                    'inputOptions' => ['class' => 'numb', 'id' => 'digit_1', 'maxlength' => 1]
                  ])->textInput() ?>
                  <?= $form->field($model, 'digit_2', [
                    'options' => ['tag' => false],
                    'template' => '{input}',
                    'inputOptions' => ['class' => 'numb', 'id' => 'digit_2', 'maxlength' => 1]
                  ])->textInput() ?>
                  <?= $form->field($model, 'digit_3', [
                    'options' => ['tag' => false],
                    'template' => '{input}',
                    'inputOptions' => ['class' => 'numb', 'id' => 'digit_3', 'maxlength' => 1]
                  ])->textInput() ?>
                  <?= $form->field($model, 'digit_4', [
                    'options' => ['tag' => false],
                    'template' => '{input}',
                    'inputOptions' => ['class' => 'numb', 'id' => 'digit_4', 'maxlength' => 1]
                  ])->textInput() ?>
                </div>
                <p>code expires in: <span class="red" id="time">00:00</span></p>
                <?= Html::submitButton('continue', ['class' => 'btn-product-detail-add-to-cart has-shadow', 'name' => 'Signup', 'onClick' => 'showLoader()']) ?>
                <?= $form->field($model, 'firstname', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'lastname', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'username', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'password', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'email', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'birth_date', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'birth_month', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'birth_year', ['template' => '{input}'])->hiddenInput();?>
                <?= $form->field($model, 'country_code', ['template' => '{input}'])->hiddenInput();?>
              <?php ActiveForm::end(); ?>
              <p>Didn’t get the code? <a href="" class="red">Resend code</a></p>
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
  $('#phone').val($(this).find('option:selected').attr('data-dialling'));
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
$('#form-signup').on('submit', function(e){
  if (!$('#agree').is(':checked')) {
    swal("", "You need to agree with our terms & conditions.", "warning");
    return false;
  }
});
JS;
$checkEmail = Url::to(['site/find-email']);
$script = str_replace('###LINK###', $checkEmail, $script);
$this->registerJs($script);
?>