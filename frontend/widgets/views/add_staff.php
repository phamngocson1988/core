<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="modalAddStaff" class="modal fade">
  <div class="modal-dialog modal-login modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-uppercase"><?=$title;?></h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => $actionUrl, 'id' => $id]); ?>
          <?= $form->field($model, 'role', ['options' => ['tag' => false], 'template' => '{input}'])->hiddenInput()->label(false) ?>
          <?= $form->field($model, 'username')->textInput(['autofocus' => true, 'placeholder' => Yii::t('app', 'Username or Email'), 'required' => 'required'])->label(false) ?>
          <div class="form-group mt-3">
            <button type="submit" class="btn btn-primary btn-lg btn-block login-btn text-uppercase"><?=Yii::t('app', 'Add');?></button>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->