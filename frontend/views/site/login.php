<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
use common\models\User;
use yii\captcha\Captcha;

$this->title = 'Login';
$this->params['breadcrumbs'][] = $this->title;
?>

<section class="page-title">
  <div class="container">
    <div class="row">
      <div class="col col-sm-12">
        <div class="page-title-content text-center pad-bot">
          <img src="/images/text-register.png" alt="">
          <p>Login</p>
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
              <p>Please note that we do not permit members to own more than (1) account.</p>
              <?= $form->field($model, 'username')->textInput()->label('Username <span class="required">*</span>') ?>
              <?= $form->field($model, 'password')->passwordInput()->label('Password <span class="required">*</span>') ?>
              <?= $form->field($model, 'captcha', [
                'inputOptions' => ['class' => 'form-control captcha-code', 'autocomplete' => false]
              ])->widget(Captcha::className(), [
                'template' => '{input}<div class="captcha-image">{image}</div>',
              ])->label('Validation Code <span class="required">*</span>') ?>

              <div class="register-action">
                <button type="submit" class="cus-btn yellow has-shadow">Login</button>
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