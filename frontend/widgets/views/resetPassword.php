<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal Reset -->
<div id="modalResetPassword" class="modal fade">
  <div class="modal-dialog modal-login modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-uppercase"><?=Yii::t('app', 'Reset password');?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => $requestUrl, 'id' => $id]); ?>
          <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'Email'), 'required' => 'required'])->label(false) ?>
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase"><?=Yii::t('app', 'Submit');?></button>
          </div>
        <?php ActiveForm::end(); ?>

        <div class="text-center">
          <p><?=Yii::t('app', 'Already a member?');?><a href="#" data-toggle="modal" data-target="#modalLogin" style="cursor: pointer;"
            data-dismiss="modal"> <?=Yii::t('app', 'Login');?></a></p>
        </div>
        <div class="text-center">
          <p><?=Yii::t('app', 'Not member yet');?><a href="#modalSignup" data-toggle="modal" style="cursor: pointer;"
            data-dismiss="modal"> <?=Yii::t('app', 'Signup');?></a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Modal Reset -->