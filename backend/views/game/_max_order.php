<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
$supplier = $model->getSupplier();
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Cập nhật số đơn / lượt của <?=$supplier ? $supplier->name : '';?></h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['class' => 'update-max-order-form'], 
  'action' => Url::to(['game/max-order'])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<?=$form->field($model, 'max_order', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
      ])->textInput()->label('Nhập số đơn / lượng');?>

      <?=$form->field($model, 'game_id', [
        'options' => ['tag' => false],
        'template' => '{input}'
      ])->hiddenInput()->label(false);?>
      <?=$form->field($model, 'supplier_id', [
        'options' => ['tag' => false],
        'template' => '{input}'
      ])->hiddenInput()->label(false);?>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Cập nhật</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
</div>
<?php ActiveForm::end()?>