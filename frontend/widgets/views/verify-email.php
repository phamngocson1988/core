<?php
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
?>
<!-- Modal Verify-->
<div class="modal fade" id="verify-email" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalCenterTitle">Verify account</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <?php $form = ActiveForm::begin(['action' => $actionUrl, 'id' => $id]); ?>
      <div class="modal-body">
        <p>To protect the security of your account, please confirm your email and request for the security token. We will send you an email with 6-digit security token that you’ll need to enter below</p>
        <div class="input-group mb-3">
          <div class="input-group-prepend mr-2">
            <button class="btn btn-request-token border-radius-3 text-left" type="button" id="<?=$buttonId;?>">Security token <br />
              <small>Request Token</small></button>
          </div>
          <?= $form->field($model, 'code', [
            'options' => ['tag' => false],
            'template' => '{input}'
          ])->textInput(['class' => 'form-control border-radius-3', 'required' => 'required', 'aria-describedby' => 'basic-addon1'])->label(false);?>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
        <button type="submit" class="btn btn-red" name="verify">Verify</button>
      </div>
      <?php ActiveForm::end(); ?>
    </div>
  </div>
</div>
<!-- End Modal Verify-->