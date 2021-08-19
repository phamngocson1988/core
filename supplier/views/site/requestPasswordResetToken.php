<?php 
use yii\widgets\ActiveForm;
use yii\helpers\Url;
$this->title = 'Forgot password';
?>
<!-- BEGIN LOGIN FORM -->
<?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'login-form']); ?>
  <h3 class="form-title font-green">Quên mật khẩu</h3>
  <div class="alert alert-danger display-hide">
    <button class="close" data-close="alert"></button>
    <span> {Yii::t('app', 'enter_username_and_password')} </span>
  </div>
  <?= $form->field($model, 'email', [
    'labelOptions' => ['class' => 'control-label visible-ie8 visible-ie9'],
    'inputOptions' => ['class' => 'form-control form-control-solid placeholder-no-fix', 'autocomplete' => 'off', 'autofocus' => true, 'placeholder' => Yii::t('app', 'email')]
  ])->textInput();?>
  
  <div class="form-actions" style="display:flex; justify-content: center;">
    <button type="submit" class="btn green uppercase">Submit</button>
  </div>
  <a href="<?=Url::to(['site/login']);?>">Trở lại đăng nhập</a>
<?php ActiveForm::end(); ?>
<!-- END LOGIN FORM -->