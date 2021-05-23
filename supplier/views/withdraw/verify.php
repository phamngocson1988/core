<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Xác nhận yêu cầu rút tiền</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'verify-request'], 
  'action' => Url::to(['withdraw/verify', 'id' => $model->id])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<?=$form->field($model, 'auth_key', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
        ])->textInput()->label('Nhập mã xác nhận');?>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end()?>