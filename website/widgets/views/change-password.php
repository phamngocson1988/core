<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal Change Password-->
<div class="modal fade" id="changepw" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Change Password</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $form = ActiveForm::begin(['action' => $actionUrl, 'id' => $id]); ?>
      <div class="modal-body">
          <?= $form->field($model, 'old_password')->passwordInput(['placeholder' => 'Old password'])->label(false);?>
          <?= $form->field($model, 'new_password')->passwordInput(['placeholder' => 'New password'])->label(false);?>
          <?= $form->field($model, 're_password')->passwordInput(['placeholder' => 'Re-type new password'])->label(false);?>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-red">Save changes</button>
      </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
<!-- End Modal Change Password-->