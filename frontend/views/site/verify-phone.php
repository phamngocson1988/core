<?php 
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
print_r(Yii::$app->getSession()->getFlash('error'));
print_r(Yii::$app->getSession()->getFlash('success'));

?>
<?php $form = ActiveForm::begin(['id' => 'form-signup', 'class' => 'rd-mailform form-fix']); ?>
<?= $form->field($model, 'digit_1')->textInput() ?>
<?= $form->field($model, 'digit_2')->textInput() ?>
<?= $form->field($model, 'digit_3')->textInput() ?>
<?= $form->field($model, 'digit_4')->textInput() ?>
<div class="form-button">
<?= Html::submitButton('Signup', ['class' => 'button button-block button-secondary button-nina', 'name' => 'Signup']) ?>
</div>
<?php ActiveForm::end(); ?>

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
                via SMS <span>(+84********09)</span> to continue
              </p>
              <div class="wrap-numb">
                <span class="numb">9</span>
                <?= $form->field($model, 'digit_1', [
                	'inputOptions' => ['class' => 'numb'],
                	'template' => '{input}'
                ])->textInput() ?>
                <span class="numb">8</span>
                <span class="numb">3</span>
                <span class="numb">7</span>
              </div>
              <p>code expires in: <span class="red">00:39</span></p>
              <a class="btn-product-detail-add-to-cart has-shadow" href="javascript:;">continue</a>
              <p>Didn’t get the code? <a href="javascript:;" class="red">Resend code</a></p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>