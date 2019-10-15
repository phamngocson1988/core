<?php 
use yii\widgets\ActiveForm;
?>
<!-- BEGIN LOGIN FORM -->
<?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'login-form']); ?>
  <h3 class="form-title font-green">Đăng nhập</h3>
  <div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span> {Yii::t('app', 'enter_username_and_password')} </span>
  </div>
  <?= $form->field($model, 'username', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'autofocus' => true, 'placeholder' => Yii::t('app', 'username')]
  ])->textInput();?>
  <?= $form->field($model, 'password', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'placeholder' => Yii::t('app', 'password')]
  ])->passwordInput();?>
  
  <div class="form-actions">
    <button type="submit" class="btn green uppercase">Submit</button>
    <?=$form->field($model, 'rememberMe', [
      'options' => ['tag' => false]
    ])->checkbox([
      'label' => 'Remember <span></span>',
      'labelOptions' => ['class' => 'rememberme check mt-checkbox mt-checkbox-outline']
    ]);?>
  </div>
<?php ActiveForm::end(); ?>
<!-- END LOGIN FORM -->