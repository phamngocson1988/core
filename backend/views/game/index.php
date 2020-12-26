<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use backend\models\Game;
use yii\widgets\ActiveForm;
use common\components\helpers\FormatConverter;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
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
          <span class="caption-subject bold uppercase"> Quản lý game</span>
          <span class="label label-info">Đang hiển thị: <?=number_format($visibleCount);?></span>
          <span class="label label-warning">Tạm ẩn: <?=number_format($invisibleCount);?></span>
          <span class="label label-danger">Hết hàng: <?=number_format($soldoutCount);?></span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['game/create', 'ref' => $ref]);?>}">Thêm mới</a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['game/index']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput()->label('Từ khóa');?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'status[]']
            ])->dropDownList([
                Game::STATUS_INVISIBLE => 'Tạm ẩn',
                Game::STATUS_VISIBLE => 'Hiển thị',
            ])->label('Trạng thái');?>

            <?=$form->field($search, 'auto_dispatcher', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'auto_dispatcher']
            ])->dropDownList([
                Game::AUTO_DISPATCHER_ON => 'ON',
                Game::AUTO_DISPATCHER_OFF => 'OFF',
            ], ['prompt' => 'Nothing selected'])->label('Phân phối tự động');?>

            <?=$form->field($search, 'soldout', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'soldout']
            ])->dropDownList([
                Game::SOLDOUT => 'Hết hàng',
                Game::INSTOCK => 'Còn hàng',
            ], ['prompt' => 'Chọn trạng thái kho'])->label('Trạng thái kho');?>


            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead>
            <tr>
              <th> Mã game </th>
              <th> Hình ảnh </th>
              <th> Tên game </th>
              <th> Trạng thái </th>
              <th> Kho </th>
              <th> TG TB Nạp </th>
              <th> Đơn / lượt </th>
              <th> Nhà cung cấp </th>
              <th> Giá </th>
              <th> Số đơn hàng </th>
              <th> Cập nhật lần cuối </th>
              <th> Tự động PPĐH </th>
              <th class="dt-center"> Tác vụ </th>
            </tr>
          </thead>
            <tbody>
              <?php if (!$models) : ?>
              <tr><td colspan="13" class="center"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
                <?php 
                $maxOrder = ArrayHelper::getValue($maxOrders, $model->id, 0);
                $lastSpeed = ArrayHelper::getValue($lastSpeeds, $model->id, 0);
                $avgSpeed = $maxOrder ? number_format(round($lastSpeed / $maxOrder)) : '--';
                ?>
              <tr>
                <td class="center"><?=$model->id;?></td>
                <td class="center"><img src="<?=$model->getImageUrl('50x50');?>" width="50px;" /></td>
                <td class="left"><?=$model->title;?></td>
                <td  class="center">
                  <?php if ($model->status == 'Y') :  ?>
                  <span class="label label-success"><?=Yii::t('app', 'visible');?></span>
                  <?php elseif ($model->status == 'N') : ?>
                  <span class="label label-warning"><?=Yii::t('app', 'disable');?></span>
                  <?php elseif ($model->status == 'D') : ?>
                  <span class="label label-default"><?=Yii::t('app', 'deleted');?></span>
                  <?php endif;?>
                </td>
                <td  class="center">
                  <?php if ($model->isSoldout()) : ?>
                    <span class="label label-default">Hết hàng</span>
                    <?php else : ?>
                    <span class="label label-success">Còn hàng</span>
                    <?php endif;?>
                </td>
                <td class="center"><?=$avgSpeed;?></td>
                <td class="center"><?=number_format($maxOrder);?></td>
                <td class="center">
                  <?php if ($model->isSoldout() || $model->isInvisible()) : ?>
                  ---
                  <?php else :?>
                  <?=number_format(ArrayHelper::getValue($suppliers, $model->id, 0));?>
                  <?php endif;?>
                </td>
                <td class="center">
                  <?php if ($model->isSoldout() || $model->isInvisible()) : ?>
                  ---
                  <?php else :?>
                  <?php $log = $model->getLastChange(); ?>
                  <?php if (!$log) :?>
                  ---
                  <?php elseif ($log->new_price > $log->old_price) :?>
                    <span class="btn btn-sm green"><i class="fa fa-arrow-up"></i>Tăng</span>
                  <?php elseif ($log->new_price < $log->old_price) : ?>
                    <span class="btn btn-sm red"><i class="fa fa-arrow-down"></i> Giảm</span>
                  <?php else: ?>
                  ---
                  <?php endif;?>
                  <?php endif;?>
                </td>
                <td class="center">
                  <?= isset($orders[$model->id]) ? number_format($orders[$model->id]) : 0;?>
                </td>
                <td class="center"><?=date('d/m/Y H:i', strtotime($model->updated_at));?></td>
                <td class="center">
                  <?php if ($model->isAutoDispatcher()) : ?>
                    <a href="<?=Url::to(['game/dispatcher', 'id' => $model->id, 'action' => 'off']);?>" class="btn btn-sm green link-action tooltips dispatcher-action" data-container="body" data-original-title="Tắt"><i class="fa fa-power-off"></i></a>
                    <?php else :?>
                    <a href="<?=Url::to(['game/dispatcher', 'id' => $model->id, 'action' => 'on']);?>" class="btn btn-sm default link-action tooltips dispatcher-action" data-container="body" data-original-title="Bật"><i class="fa fa-power-off"></i></a>
                  <?php endif;?>
                </td>
                <td class="center">
                    <a href='<?=Url::to(['game/edit', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-sm grey-salsa tooltips" data-container="body" data-original-title="Chỉnh sửa" data-pjax="0"><i class="fa fa-pencil"></i></a>
                    <a href='<?=Url::to(['game/delete', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-sm grey-salsa delete-action tooltips" data-container="body" data-original-title="Xóa" data-pjax="0"><i class="fa fa-trash-o"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".delete-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn xóa game này?',
  callback: function(eletement, data) {
    location.reload();
  }
});

$(".dispatcher-action").ajax_action({
  callback: function(eletement, data) {
    if (data.action == 'on') {
      window.location.href = data.next;
    } else {
      location.reload();
    }
  },
  error: function(element, errors) {
      alert(errors);
  },
});
JS;
$this->registerJs($script);
?>