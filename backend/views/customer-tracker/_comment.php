<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Nhập ghi chú</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'add-comment-form'], 
  'action' => Url::to(['lead-tracker/add-comment', 'id' => $id])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<textarea name="content" class="form-group col-md-12 col-lg-12"></textarea>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end()?>