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

<section class="page-title" id="page-title">
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
              <?= $form->field($model, 'username', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->textInput()->label('Username <span class="required">*</span>');?>
              <?= $form->field($model, 'password', [
                'template' => '{input}{label}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ']
              ])->passwordInput()->label('Password <span class="required">*</span>');?>
              <?= $form->field($model, 'captcha', [
                'template' => '{label}{input}{error}',
                'options' => ['class' => 'form-group t-input'],
                'labelOptions' => ['class' => 'placeholder'],
                'inputOptions' => ['placeholder' => ' ', 'class' => 'form-control captcha-code', 'autocomplete' => false]
              ])->widget(Captcha::className(), [
                'template' => '{input}<div class="captcha-image">{image}</div>',
              ])->label('Validation Code <span class="required">*</span>') ?>

              <div class="register-action">
                <button type="submit" class="cus-btn yellow has-shadow">Login</button>
                <div class="reg-login-now"><a href="<?=Url::to(['site/signup', '#' => 'page-title']);?>">Register now</a></div>
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