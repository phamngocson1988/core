<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="modalSignup" class="modal fade">
  <div class="modal-dialog modal-login ">
    <div class="modal-content">
      <div class="modal-header">
        <div class="avatar">
          <img src="/images/avatar.png" alt="Avatar">
        </div>
        <h3 class="modal-title text-uppercase">Signup</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <p class="text-center">with your social network</p>
        <ul class="list-inline text-center">
          <?php $authAuthChoice = \yii\authclient\widgets\AuthChoice::begin([
            'baseAuthUrl' => ['site/auth'],
            'popupMode' => false,
          ]); ?>
          <?php foreach ($authAuthChoice->getClients() as $key => $client): ?>
            <?php if ($key == 'google') continue;?>
            <li class="list-inline-item <?=$key;?>"><?= $authAuthChoice->clientLink($client) ?></li>
          <?php endforeach; ?>
          <?php \yii\authclient\widgets\AuthChoice::end(); ?>
        </ul>
        <div class="text-horizontal"><span>or</span></div>
        <?php $form = ActiveForm::begin(['action' => Url::to(['site/login']), 'id' => $id]); ?>
          <?= $form->field($model, 'email')->textInput(['autofocus' => true, 'placeholder' => 'Email', 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'required' => 'required'])->label(false) ?>
          <div class="form-group">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase">Signup</button>
          </div>
        <?php ActiveForm::end(); ?>
        <p class="text-center">By signing up you agree to the <a href="#" class="terms">terms of service and privacy
          policy</a>
        </p>
        <div class="text-center">
          <p>Already a member?<a href="#" data-toggle="modal" data-target="#modalLogin" style="cursor: pointer;"
            data-dismiss="modal"> Login here</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->