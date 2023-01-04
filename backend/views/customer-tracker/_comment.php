<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Nhập contact log</h4>
</div>
<?php $form = ActiveForm::begin();?>
<div class="modal-body"> 
  <?=$form->field($model, 'reason')->textInput()->label('Lý do');?>
  <?=$form->field($model, 'content')->textInput()->label('Ghi chú');?>
  <?=$form->field($model, 'plan')->textInput()->label('Hành động');?>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end()?>