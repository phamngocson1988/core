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
?>

<div class="section-lg text-center">
  <div class="container">
    <h3>Register</h3>
    <div class="row row-fix justify-content-sm-center">
      <div class="col-md-8 col-lg-6 col-xl-4">
        <!-- RD Mailform-->
        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix', 'options' => ['autocomplete' => 'off']]); ?>
          <?= $form->field($model, 'email', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Input your email', 'id' => 'email'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->textInput() ?>

          <?= $form->field($model, 'name', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Input your name'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->textInput() ?>

          <?= $form->field($model, 'country_code', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input select-filter', 'data-placeholder' => 'Chọn game yêu thích'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->dropDownList(Yii::$app->params['country_code']) ?>

          <?= $form->field($model, 'phone', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Input your phone'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->textInput() ?>

          <?= $form->field($model, 'birthday', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'readonly' => true, 'placeholder' => 'Input your birthday'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          // ])->widget(\yii\jui\DatePicker::className(),['clientOptions' => ['changeMonth' => true, 'changeYear' => true], "dateFormat" => "yyyy-MM-dd"]) 
          ])->widget(\dosamigos\datepicker\DatePicker::className(), [
            'inline' => false, 
            'template' => '<div class="input-group date" data-provide="datepicker">{input}<div class="input-group-addon" style="border-radius: 0px 35px 35px 0px"><i class="mdi mdi-calendar"></i></div></div>',
            'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd'
            ]
          ]);
          ?>

          <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Input your password'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->passwordInput() ?>
          <?= $form->field($model, 'repassword', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Re-type your password'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->passwordInput() ?>

          <?= $form->field($model, 'invite_code', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Invite code'],
            'labelOptions' => ['class' => 'form-label'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->textInput() ?>

          <?= $form->field($model, 'is_reseller', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input select-filter', 'data-placeholder' => 'Choose type of account'],
            'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
            'template' => '{input}{hint}{error}'
          ])->dropDownList(User::getResellerStatus()) ?>

          <?= $form->field($model, 'verifyCode', [
            'options' => ['class' => 'form-wrap form-wrap-validation'],
            'inputOptions' => ['class' => 'form-input', 'placeholder' => 'Input captcha'],
            'labelOptions' => ['class' => 'form-label'],
            'template' => '{input}{hint}{error}'
          ])->widget(Captcha::className(), [
            'template' => '<div class="row"><div class="col-md-7">{input}</div><div class="col-md-5">{image}</div></div>',
            'imageOptions' => ['class' => 'img-responsive', 'width' => '100%', 'height' => '100%']
          ])->label('Captcha') ?>


          <div class="form-button">
            <?= Html::submitButton('Signup', ['class' => 'button button-block button-secondary button-nina', 'name' => 'Signup']) ?>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
    <p class="offset-custom-1 text-gray-light"><a href="<?=Url::to(['site/login']);?>" style="color:white">Signin now</a></p>
    <div class="group-xs group-middle"><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-facebook" href="#"></a><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-twitter" href="#"></a><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-google" href="#"></a></div>
  </div>
</div>
<?php
$script = <<< JS
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
      }
    },
  });
})
JS;
$checkEmail = Url::to(['site/find-email']);
$script = str_replace('###LINK###', $checkEmail, $script);
$this->registerJs($script);
?>