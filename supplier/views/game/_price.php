<?php
use yii\widgets\ActiveForm;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use common\components\helpers\StringHelper;

$game = $model->getGame();
$supplierGame = $model->getSupplierGame();
?>

<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
	<h4 class="modal-title">Cập nhật giá game <?=$game->title;?></h4>
</div>

<?php if ($supplierGame->isAutoDispatcher()) : ?>
<div class="modal-body" style="word-wrap: break-word"> 
Tạm thời bạn không thể thực hiện thao tác cập nhật giá, vui lòng liên hệ nhân viên hỗ trợ. Nếu điều này ảnh hưởng đến quyết định nhận đơn, vui lòng chọn <strong>Dừng nhận đơn</strong>
</div>
<div class="modal-footer">
	<button type="button" class="btn dark btn-outline" data-dismiss="modal">Tiếp tục nhận đơn</button>
	<a type="button" class="btn green link-action" href="<?=Url::to(['game/disable', 'id' => $game->id]);?>">Dừng nhận đơn</a>
</div>
<?php else : ?>
<?php $activeForm = ActiveForm::begin(['options' => ['class' => 'form-row-seperated', 'id' => 'update-price-form'], 'action' => Url::to(['game/price', 'id' => $game->id])]);?>
<div class="modal-body"> 
<?=$activeForm->field($model, 'price')->textInput();?>
</div>
<div class="modal-footer">
	<button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
	<button type="submit" class="btn green">Xác nhận</button>
</div>
<?php ActiveForm::end();?>
<?php endif; ?>
