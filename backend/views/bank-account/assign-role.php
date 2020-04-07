<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\widgets\CheckboxInput;

$roles = [];
foreach ($model->getRoles() as $key => $value) {
  $roles[$key] = sprintf("%s <span></span>", $value);
}
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Gửi đơn hàng cho nhà cung cấp</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'assign-role-form'], 
  'action' => Url::to(['bank-account/assign-role', 'id' => $id])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
      <div class="form-body">
        <?=$form->field($model, 'roles', [
          'labelOptions' => ['class' => 'col-md-2 control-label'],
          'template' => '<div class="col-md-10">{input}{hint}{error}</div>'
        ])->checkboxList($roles, [
          'class' => 'md-checkbox-list', 
          'encode' => false , 
          'itemOptions' => ['labelOptions' => ['class'=>'mt-checkbox', 'style' => 'display: block']]
        ])->label('Categories');?>
      </div>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Cập nhật</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
</div>
<?php ActiveForm::end()?>