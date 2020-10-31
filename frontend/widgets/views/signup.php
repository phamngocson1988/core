<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="modalSignup" class="modal fade">
  <div class="modal-dialog modal-login modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-uppercase"><?=Yii::t('app', 'Signup');?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => $signupUrl, 'id' => $id]); ?>
          <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'username'), 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'email')->textInput(['placeholder' => Yii::t('app', 'Email'), 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'password'), 'required' => 'required'])->label(false) ?>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase"><?=Yii::t('app', 'Signup');?></button>
          </div>
        <?php ActiveForm::end(); ?>
        <p class="text-center"><?=Yii::t('app', 'By signing up you agree to the <a href="{link}" class="terms">terms of service and privacy policy</a>', ['link' => 'javascript;']);?>
        </p>
        <div class="text-center">
          <p><?=Yii::t('app', 'Already a member?');?><a href="#" data-toggle="modal" data-target="#modalLogin" style="cursor: pointer;"
            data-dismiss="modal"> <?=Yii::t('app', 'Login');?></a></p>
        </div>
        <div class="text-center">
          <p><?=Yii::t('app', 'Forgot password?');?><a href="#modalResetPassword" data-toggle="modal" style="cursor: pointer;"
            data-dismiss="modal"> <?=Yii::t('app', 'Reset password');?></a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->