<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal SignUp -->
<div id="unclockModal" class="modal fade">
  <div class="modal-dialog modal-login modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title text-uppercase">Mở khoá tài khoản</h3>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
      </div>
      <div class="modal-body">
        <?php $form = ActiveForm::begin(['action' => $unclockUrl, 'id' => $formId]); ?>
          <?= $form->field($model, 'password')->passwordInput(['placeholder' => Yii::t('app', 'password'), 'required' => 'required'])->label(false) ?>
          <div class="form-group mt-3">
            <button type="button" class="btn btn-primary btn-lg btn-block login-btn text-uppercase">Mở khoá</button>
          </div>
        <?php ActiveForm::end(); ?>
      </div>
    </div>
  </div>
</div>
<!--End Modal SignUp -->