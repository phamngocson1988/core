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
        <?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix']); ?>
          <?= $form->field($model, 'firstname')->textInput() ?>
          <?= $form->field($model, 'lastname')->textInput() ?>
          <?= $form->field($model, 'username')->textInput() ?>
          <?= $form->field($model, 'password')->passwordInput() ?>
          <?= $form->field($model, 'repassword')->passwordInput() ?>
          <?= $form->field($model, 'email')->textInput() ?>
          <?= $form->field($model, 'birth_date', [
            'inputOptions' => ['class' => 'form-control', 'id' => 'birth_date']
          ])->textInput() ?>
          <?= $form->field($model, 'birth_month', [
            'inputOptions' => ['class' => 'form-control', 'id' => 'birth_month']
          ])->textInput() ?>
          <?= $form->field($model, 'birth_year', [
            'inputOptions' => ['class' => 'form-control', 'id' => 'birth_year']
          ])->textInput() ?>
          <?= $form->field($model, 'currency')->textInput() ?>
          <?= $form->field($model, 'country_code')->textInput() ?>
          <?= $form->field($model, 'phone')->textInput() ?>


          <?= $form->field($model, 'captcha', [
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
$('#birth_year, #birth_month, #birth_date').on('change', function() {
  correctDate($('#birth_year'),$('#birth_month'),$('#birth_date'));
  console.log('date trigger');
});
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
})
JS;
$checkEmail = Url::to(['site/find-email']);
$script = str_replace('###LINK###', $checkEmail, $script);
$this->registerJs($script);
?>