<?php
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Forgot password';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="page-title" id="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center pad-bot">
          <img src="/images/text-register.png" alt="">
          <p>Forgot password</p>
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
            <?php $form = ActiveForm::begin(['id' => 'form-login', 'options' => ['autocomplete' => 'off']]); ?>
              <p>We will send an email to you.</p>
              <?= $form->field($model, 'email', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->textInput()->label('Email <span class="required">*</span>') ?>
              <div class="register-action">
                <button type="submit" class="cus-btn yellow has-shadow">Send</button>
              </div>
            <?php ActiveForm::end(); ?>
          </div>
        </div>
        <div class="col col-12 col-lg-1 col-md-1 col-sm-12"></div>
        <div class="col col-12 col-lg-4 col-md-4 col-sm-12">
        </div>
      </div>
    </div>
  </div>
</section>