<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Cập nhật game vào chương trình khuyến mãi</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'edit-game-form'], 
  'action' => Url::to(['flashsale/edit-game', 'id' => $id])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<?=$form->field($model, 'game_id', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
        'inputOptions' => ['class' => 'form-control', 'disabled' => true]
      ])->dropdownList($model->fetchGame())->label('Chọn game');?>

      <?=$form->field($model, 'price', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
      ])->textInput()->label('Giá bán');?>

      <?=$form->field($model, 'limit', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
      ])->textInput()->label('Số lượng');?>

      <?=$form->field($model, 'remain', [
        'options' => ['class' => 'form-group col-md-12 col-lg-12'],
      ])->textInput()->label('Còn lại');?>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Thêm game</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
</div>
<?php ActiveForm::end()?>