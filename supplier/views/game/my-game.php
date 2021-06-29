<?php 
use yii\widgets\LinkPager;
use yii\helpers\Url;
use supplier\behaviors\UserSupplierBehavior;
use yii\widgets\ActiveForm;

$supplier = Yii::$app->user->getIdentity();
$supplier->attachBehavior('supplier', new UserSupplierBehavior);
$isAdvanceMode = Yii::$app->user->isAdvanceMode();
$column = 6;
if ($isAdvanceMode) $column++;
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
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th> <?=Yii::t('app', 'ID');?> </th>
                <th> <?=Yii::t('app', 'image');?> </th>
                <th> <?=Yii::t('app', 'title');?> </th>
                <?php if ($isAdvanceMode) :?>
                <th> <?=Yii::t('app', 'price');?> </th>
                <?php endif;?>
                <th> Trạng thái ở Kinggems </th>
                <th> Trạng thái của nhà cung cấp </th>
                <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) : ?>
                <tr><td colspan="<?=$column;?>"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $supplierGame) : ?>
                <?php $game = $supplierGame->game;?>
                <tr>
                  <td><?=$game->id;?></td>
                  <td><img src="<?=$game->getImageUrl('50x50');?>" width="50px;" /></td>
                  <td><?=$game->title;?></td>
                  <?php if ($isAdvanceMode) :?>
                  <td><?=number_format($supplierGame->price, 1);?></td>
                  <?php endif;?>
                  <td>
                    <?php if ($game->isVisible()) : ?>
                    <span class="label label-success"><?=Yii::t('app', 'visible');?></span>
                    <?php elseif ($game->isInvisible()) : ?>
                    <span class="label label-warning"><?=Yii::t('app', 'disable');?></span>
                    <?php elseif ($game->isDeleted()) : ?>
                    <span class="label label-default"><?=Yii::t('app', 'deleted');?></span>
                    <?php endif;?>
                  </td>
                  <td>
                    <?php if ($supplierGame->isEnabled()) : ?>
                    <span class="label label-success">Enabled</span>
                    <?php elseif ($supplierGame->isDisabled()) : ?>
                    <span class="label label-warning">Disabled</span>
                    <?php endif;?>
                  </td>
                  <td>
                    <a href="<?=Url::to(['game/remove', 'id' => $game->id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Hủy đăng ký"><i class="fa fa-times"></i> Hủy đăng ký </a>
                    <?php if ($supplierGame->isEnabled()) : ?>
                    <a href="<?=Url::to(['game/disable', 'id' => $game->id]);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Tạm ngưng"><i class="fa fa-arrow-down"></i> Tạm ngưng </a>
                    <?php else :?>
                    <?php if ($supplierGame->price > 0) : ?>
                    <a href="<?=Url::to(['game/enable', 'id' => $game->id]);?>" class="btn btn-sm red link-action tooltips" data-container="body" data-original-title="Kích hoạt"><i class="fa fa-arrow-up"></i> Kích hoạt </a>
                    <?php endif;?>
                    <?php endif;?>

                    <?php if (Yii::$app->user->isAdvanceMode()) : ?>
                    <a href="<?=Url::to(['game/price', 'id' => $game->id]);?>" data-target="#price-modal" class="btn btn-sm purple tooltips" data-container="body" data-original-title="Cập nhật giá" data-toggle="modal"><i class="fa fa-arrow-up"></i> Cập nhật giá </a>
                    <?php endif;?>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages]);?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php if (Yii::$app->user->isAdvanceMode() && $models) : ?>
<div class="modal fade" id="price-modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">

    </div>
  </div>
</div>
<?php endif;?>
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
var updatePriceForm = new AjaxFormSubmit({element: '#update-price-form'});
updatePriceForm.success = function (data, form) {
  setTimeout(function(){
      location.reload();
  },2000); //delay is in milliseconds 
  toastr.success('Bạn đã cập nhật giá thành công');
  
};
updatePriceForm.error = function (error) {
  toastr.error(error);
}
JS;
$this->registerJs($script);
?>