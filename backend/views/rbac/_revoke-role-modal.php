<?php
use yii\widgets\ActiveForm;
use yii\helpers\Url;
?>
<div class="modal-header">
  <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
  <h4 class="modal-title">Loại bỏ vai trò</h4>
</div>
<?php $form = ActiveForm::begin([
  'options' => ['id' => 'revoke-role-form'], 
  'action' => Url::to(['rbac/revoke-role', 'user_id' => $user->id, 'role' => $role->name])
]);?>
<div class="modal-body"> 
  <div class="row">
    <div class="col-md-12">
    	<p>Bạn có muốn loại bỏ quyền <strong><?=$role->description;?></strong> của nhân viên <strong><?=$user->name;?></strong></p>
    </div>
  </div>
</div>
<div class="modal-footer">
  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-check"></i> Chấp nhận</button>
  <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
</div>
<?php ActiveForm::end()?>