<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<section class="verify-code">
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
                <?= Html::submitButton('continue', ['class' => 'btn-product-detail-add-to-cart has-shadow', 'name' => 'Signup']) ?>
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
var counter = null;
function startTimer(duration, display) {
    var timer = duration, minutes, seconds;
    counter = setInterval(function () {
        minutes = parseInt(timer / 60, 10);
        seconds = parseInt(timer % 60, 10);
        minutes = minutes < 10 ? "0" + minutes : minutes;
        seconds = seconds < 10 ? "0" + seconds : seconds;
        display.text(minutes + ":" + seconds);
        if (--timer < 0) {
          clearInterval(counter);
        }
    }, 1000);
}
startTimer(60, $('#time'));

$('#digit_1').on('input', function() {
  if ($(this).val()) $('#digit_2').focus();
});
$('#digit_2').on('input', function() {
  if ($(this).val()) $('#digit_3').focus();
});
$('#digit_3').on('input', function() {
  if ($(this).val()) $('#digit_4').focus();
});
$('form').submit(function(){
    $('input[type=submit]', this).attr('disabled', 'disabled');
});
JS;
$this->registerJs($script);
?>