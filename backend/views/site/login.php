<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use common\widgets\CheckboxInput;
use yii\helpers\Url;

$this->title = Yii::t('app', 'lbl_login');
?>
<?php $form = ActiveForm::begin(['id' => 'login-form']); ?>
  <h3 class="form-title font-green"><?=Yii::t('app', 'lbl_login');?></h3>
  <div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span> <?=Yii::t('app', 'lbl_input_username_password');?> </span>
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
    <button type="submit" class="btn green uppercase"><?=Yii::t('app', 'btn_login');?></button>
    <!-- <?=$form->field($model, 'rememberMe', [
      'options' => ['tag' => false]
    ])->widget(CheckboxInput::className())->label(false);?> -->
  </div>
<?php ActiveForm::end(); ?>
<?php
$jsScript = <<< JS
$('#login-form input').keyup(function (event) {
  if (event.which == 13) {
    $('form#login-form').submit();
  }
});
JS;
$this->registerJs($jsScript);
?>       
