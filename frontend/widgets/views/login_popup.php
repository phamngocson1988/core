<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use yii\helpers\Url;
?>
<div class="modal modal-custom modal-account fade" id="<?=$popup_id;?>" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" type="button" data-dismiss="modal" aria-label="Close"><span aria-hidden="true"></span></button>
      </div>
      <div class="modal-body text-center">
        <div class="modal-body-inner">
          <figure class="modal-account-image"><img src="/images/registration-modal-01-510x807.jpg" alt="" width="510" height="807"/>
          </figure>
        </div>
        <div class="modal-body-inner">
          <h4 class="modal-title">registration</h4>
          <!-- RD Mailform-->
          <?php $form = ActiveForm::begin(['id' => 'login-form', 'class' => 'rd-mailform form-novi', 'action' => ['site/ajax-login']]); ?>
            <?= $form->field($model, 'username', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{input}{label}{hint}{error}'
            ])->textInput(['autofocus' => true]) ?>

            <?= $form->field($model, 'password', [
              'options' => ['class' => 'form-wrap form-wrap-validation'],
              'inputOptions' => ['class' => 'form-input'],
              'labelOptions' => ['class' => 'form-label'],
              'errorOptions' => ['tag' => 'span', 'class' => 'form-validation'],
              'template' => '{input}{label}{hint}{error}'
            ])->passwordInput() ?>
            <div class="form-button">
              <?= Html::submitButton('Sign In', ['class' => 'button button-block button-secondary button-nina']) ?>
            </div>
          <?php ActiveForm::end(); ?>
          <p class="offset-custom-1 text-gray-light">or visit</p>
          <div class="group-xs group-middle"><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-facebook" href="#"></a><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-twitter" href="#"></a><a class="icon icon-md-smaller icon-circle icon-filled mdi mdi-google" href="#"></a></div>
        </div>
      </div>
    </div>
  </div>
</div>