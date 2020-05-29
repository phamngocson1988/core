<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="modalLogin" class="modal fade">
  <div class="modal-dialog modal-login ">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-uppercase">Login</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => $loginUrl, 'id' => $id]); ?>
          <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => 'Username', 'required' => 'required'])->label(false) ?>
          <?= $form->field($model, 'password')->passwordInput(['placeholder' => 'Password', 'required' => 'required'])->label(false) ?>
          <div class="d-flex bd-highlight">
          	<?=$form->field($model, 'rememberMe', [
				      'options' => ['class' => 'form-check flex-fill'],
              'labelOptions' => ['class' => 'form-check-label'],
              'template' => '{input}{label}'
				    ])->checkbox(['class' => 'form-check-input', 'style' => "margin-top:6px"], false);?>
            <div class="flex-fill ml-auto">
              <a href="#modalProblem" data-toggle="modal" style="cursor: pointer;" data-dismiss="modal">Problem to
              login?</a>
            </div>
          </div>
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase">Login</button>
          </div>
        <?php ActiveForm::end(); ?>

        <div class="text-center">
          <p>Not a member yet?<a href="#modalSignup" data-toggle="modal" style="cursor: pointer;"
            data-dismiss="modal"> Sign up</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->