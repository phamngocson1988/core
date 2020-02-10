<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Bạn đang hủy yêu cầu rút tiền của nhà cung cấp</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'cancel-withdraw-form'], 
  'action' => Url::to(['supplier/cancel-withdraw', 'id' => $model->id])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<?=$form->field($model, 'note', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
      ])->textInput()->label('Ghi chú');?>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
</div>
<?php ActiveForm::end()?>