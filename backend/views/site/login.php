<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\CheckboxInput;
use yii\helpers\Url;

$this->title = 'Đăng nhập';
?>
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
  <h3 class="form-title font-green">Đăng nhập</h3>
  <div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span> Nhập tài khoản và mật khẩu </span>
  </div>
  <?=$form->field($model, 'username', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'autofocus' => true, 'placeholder' => Yii::t('app', 'username')]
  ])->textInput();?>
  <?=$form->field($model, 'password', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'password')]
  ])->passwordInput();?>
  
  <div class="form-actions">
    <button type="submit" class="btn green uppercase">Đăng nhập</button>
    <!-- <?=$form->field($model, 'rememberMe', [
      'options' => ['tag' => false]
    ])->widget(CheckboxInput::className())->label(false);?> -->
  </div>
<?php ActiveForm::end(); ?>
