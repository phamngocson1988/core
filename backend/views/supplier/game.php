<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use backend\behaviors\UserSupplierBehavior;
use yii\widgets\ActiveForm;

?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <a href="<?=Url::to(['supplier/index']);?>">Nhà cung cấp</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Quản lý game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý game</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Game của nhà cung cấp <strong><?=$supplier->name;?></strong></span>
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
              <th> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $supplierGame) : ?>
              <?php $model = $supplierGame->game;?>
              <tr>
                <td><?=$model->id;?></td>
                <td><img src="<?=$model->getImageUrl('50x50');?>" width="50px;" /></td>
                <td><?=$model->title;?></td>
                <td><?=number_format($supplierGame->price, 1);?></td>
                <td>
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
                <td>
                  <a href="<?=Url::to(['game/suppliers', 'id' => $model->id]);?>" target="_blank" class="btn btn-sm blue tooltips" data-container="body" data-original-title="Giá nhà cung cấp khác"><i class="fa fa-list"></i></a>

                  <?php if ($supplierGame->isEnabled()) : ?>
                    <a href="<?=Url::to(['supplier-game/disable', 'game_id' => $supplierGame->game_id, 'supplier_id' => $supplierGame->supplier_id]);?>" class="btn btn-sm green link-action tooltips action-link" data-container="body" data-original-title="Tạm ngưng game"><i class="fa fa-power-off"></i></a>
                    <?php else :?>
                    <a href="<?=Url::to(['supplier-game/enable', 'game_id' => $supplierGame->game_id, 'supplier_id' => $supplierGame->supplier_id]);?>" class="btn btn-sm default link-action tooltips action-link" data-container="body" data-original-title="Kích hoạt game"><i class="fa fa-power-off"></i></a>
                  <?php endif;?>
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