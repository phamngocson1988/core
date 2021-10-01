<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="modalLogin" class="modal fade">
  <div class="modal-dialog modal-login ">
    <div class="modal-content">
      <div class="modal-header">
        <div class="avatar">
          <img src="/images/avatar.png" alt="Avatar">
        </div>
        <h3 class="modal-title text-uppercase">Login</h3>
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
            <li class="list-inline-item"><?= $authAuthChoice->clientLink($client) ?></li>
          <?php endforeach; ?>
          <?php \yii\authclient\widgets\AuthChoice::end(); ?>
        </ul>
        <div class="text-horizontal"><span>or</span></div>
        <?php $form = ActiveForm::begin(['id' => $id]); ?>
          <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'id' => 'username', 'scenario' => $loginScenario, 'placeholder' => 'Username', 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'id' => 'password', 'scenario' => $loginScenario, 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'securityCode', ['options' => ['style' => 'display:none', 'scenario' => $verifyScenario], 'hintOptions' => ['class' => 'hint-block']])
          ->textInput(['placeholder' => 'Verification Code', 'id' => 'securityCode'])
          ->hint('Verification code is sent to your email')
          ->label(false) ?>
          <div class="d-flex bd-highlight" style="justify-content: space-between">
          	<?=$form->field($model, 'rememberMe', [
				      'options' => ['class' => 'form-check flex-fill', 'id' => 'rememberMe', 'scenario' => $loginScenario],
              'labelOptions' => ['class' => 'form-check-label'],
              'template' => '{input}{label}'
				    ])->checkbox(['class' => 'form-check-input', 'style' => "margin-top:6px"], false);?>
            <a href="javascript:;" id="back-login-form" scenario="<?=$verifyScenario;?>" style="cursor: pointer; display: none">Back to login</a>
            <a href="#modalProblem" data-toggle="modal" style="cursor: pointer;" data-dismiss="modal">Problem to login?</a>
          </div>
          
          <input type="hidden" id="scenario" name="scenario" value="<?=$scenario;?>" />
          <div class="form-group mt-3">
            <button type="submit" id="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase">Login</button>
          </div>
        <?php ActiveForm::end(); ?>

        <div class="text-center">
          <p style="margin-bottom: 5px; display: none" scenario="<?=$verifyScenario;?>">Cannot receive the code?<a href="javascript:;" id="login-resend-code-button" style="cursor: pointer;"> Resend code</a></p>
          <p>Not a member yet?<a href="#modalSignup" data-toggle="modal" style="cursor: pointer;"
            data-dismiss="modal"> Sign up</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->