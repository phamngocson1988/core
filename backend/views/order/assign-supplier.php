<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\components\helpers\StringHelper;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Gửi đơn hàng cho nhà cung cấp</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'assign-supplier'], 
  'action' => Url::to(['order/assign-supplier', 'id' => $id, 'ref' => $ref])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<?php if (!$suppliers) : ?>
    	<label>Không có nhà cung cấp nào</label>
    	<?php else : ?>
    	<?=$form->field($model, 'supplier_id', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
      ])->dropdownList($suppliers)->label('Chọn nhà cung cấp. <strong style="font-size: 18px">Giá vốn (VNĐ): ' . StringHelper::numberFormat($order->cogs_price * $order->rate_usd, 2) . '</strong>');?>
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