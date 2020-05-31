<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="modalSignup" class="modal fade">
  <div class="modal-dialog modal-login ">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-uppercase"><?=Yii::t('app', 'signup');?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => $signupUrl, 'id' => $id]); ?>
          <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'username'), 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'email')->textInput(['placeholder' => Yii::t('app', 'email'), 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'password'), 'required' => 'required'])->label(false) ?>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase"><?=Yii::t('app', 'signup');?></button>
          </div>
        <?php ActiveForm::end(); ?>
        <p class="text-center">By signing up you agree to the <a href="#" class="terms">terms of service and privacy
          policy</a>
        </p>
        <div class="text-center">
          <p>Already a member?<a href="#" data-toggle="modal" data-target="#modalLogin" style="cursor: pointer;"
            data-dismiss="modal"> <?=Yii::t('app', 'login');?></a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->