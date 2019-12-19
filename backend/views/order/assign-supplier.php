<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
?>
  <div class="modal-header">
    <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
    <h4 class="modal-title">Gửi đơn hàng cho nhà cung cấp</h4>
  </div>
  <?php $form = ActiveForm::begin();?>
  <div class="modal-body"> 
    <div class="row">
      <div class="col-md-12">
      	<?php if (!$suppliers) : ?>
      	<label>Không có nhà cung cấp nào</label>
      	<?php else : ?>
      	<?=$form->field($order, 'supplier_id', [
          'options' => ['class' => 'form-group col-md-12 col-lg-12'],
        ])->dropdownList($suppliers)->label('Chọn nhà cung cấp');?>
    	<?php endif; ?>
      </div>
    </div>
  </div>
  <div class="modal-footer">
  	<?php if ($suppliers) : ?>
    <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
	<?php endif; ?>
    <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
  </div>
<?php ActiveForm::end()?>
