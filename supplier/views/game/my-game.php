<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use supplier\behaviors\UserSupplierBehavior;
use yii\widgets\ActiveForm;

$supplier = Yii::$app->user->getIdentity();
$supplier->attachBehavior('supplier', new UserSupplierBehavior);
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['game/index']);?>"><?=Yii::t('app', 'manage_games');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Game của tôi</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Game của tôi</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Game của tôi</span>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> <?=Yii::t('app', 'ID');?> </th>
              <th> <?=Yii::t('app', 'image');?> </th>
              <th> <?=Yii::t('app', 'title');?> </th>
              <th> <?=Yii::t('app', 'price');?> </th>
              <th> Trạng thái ở Kinggems </th>
              <th> Trạng thái của nhà cung cấp </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $supplierGame) : ?>
              <?php $model = $supplierGame->game;?>
              <tr>
                <td style="vertical-align: middle;"><?=$model->id;?></td>
                <td style="vertical-align: middle;"><img src="<?=$model->getImageUrl('50x50');?>" width="50px;" /></td>
                <td style="vertical-align: middle;"><?=$model->title;?></td>
                <td style="vertical-align: middle;"><?=number_format($supplierGame->price, 1);?></td>
                <td style="vertical-align: middle;">
                  <?php if ($model->isVisible()) : ?>
                  <span class="label label-success"><?=Yii::t('app', 'visible');?></span>
                  <?php elseif ($model->isInvisible()) : ?>
                  <span class="label label-warning"><?=Yii::t('app', 'disable');?></span>
                  <?php elseif ($model->isDeleted()) : ?>
                  <span class="label label-default"><?=Yii::t('app', 'deleted');?></span>
                  <?php endif;?>
                </td>
                <td style="vertical-align: middle;">
                  <?php if ($supplierGame->isEnabled()) : ?>
                  <span class="label label-success">Enabled</span>
                  <?php elseif ($supplierGame->isDisabled()) : ?>
                  <span class="label label-warning">Disabled</span>
                  <?php endif;?>
                </td>
                <td style="vertical-align: middle;">
                  <a href="<?=Url::to(['game/remove', 'id' => $model->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Hủy đăng ký"><i class="fa fa-times"></i> Hủy đăng ký </a>
                  <?php if ($supplierGame->isEnabled()) : ?>
                  <a href="<?=Url::to(['game/disable', 'id' => $model->id]);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Tạm ngưng"><i class="fa fa-arrow-down"></i> Tạm ngưng </a>
                  <?php else :?>
                  <a href="<?=Url::to(['game/enable', 'id' => $model->id]);?>" class="btn btn-sm red link-action tooltips" data-container="body" data-original-title="Kích hoạt"><i class="fa fa-arrow-up"></i> Kích hoạt </a>
                  <?php endif;?>
                  <a href="#price-modal-<?=$model->id;?>" class="btn btn-sm purple tooltips" data-container="body" data-original-title="Cập nhật giá" data-toggle="modal"><i class="fa fa-arrow-up"></i> Cập nhật giá </a>
                  <div class="modal fade" id="price-modal-<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Cập nhật giá game <?=$model->title;?></h4>
                        </div>
                        <?php $priceForm = ActiveForm::begin(['options' => ['class' => 'form-row-seperated update-price-form', 'game-id' => $model->id], 'action' => Url::to(['game/price', 'id' => $model->id])]);?>
                        <div class="modal-body"> 
                          <?=$priceForm->field($supplierGame, 'price', ['inputOptions' => ['class' => 'form-control', 'id' => 'supplier-price-' . $model->id]])->textInput();?>
                        </div>
                        <div class="modal-footer">
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                          <button type="submit" class="btn green">Xác nhận</button>
                        </div>
                        <?php ActiveForm::end();?>
                      </div>
                    </div>
                  </div>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});

// update price
var updatePriceForm = new AjaxFormSubmit({element: '.update-price-form'});
updatePriceForm.success = function (data, form) {
  location.reload();
};

updatePriceForm.validate = function(form) {
  var id = $(form).attr('game-id');
  var price = $.trim($(form).find('#supplier-price-' + id).val());
  if (price == '') {
    return false;
  }
  return true;
}

updatePriceForm.invalid = function(form) {
  alert('Nội dung không được để trống');
}
JS;
$this->registerJs($script);
?>