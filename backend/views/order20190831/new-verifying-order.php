<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
?>

<style>
.hide-text {
    white-space: nowrap;
    width: 100%;
    max-width: 500px;
    text-overflow: ellipsis;
    overflow: hidden;
}
</style>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Đơn hàng chưa thanh toán</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng chưa thanh toán</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng chưa thanh toán</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> Mã đơn hàng </th>
              <th style="width: 10%;"> Tên game </th>
              <th style="width: 10%;"> Ngày tạo </th>
              <th style="width: 5%;"> Số lượng nạp </th>
              <th style="width: 5%;"> Số gói </th>
              <th style="width: 10%;"> Người bán hàng </th>
              <th style="width: 10%;"> Trạng thái </th>
              <th style="width: 10%;"> Nhà cung cấp </th>
              <th style="width: 10%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="9"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td style="vertical-align: middle;"><a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                <td style="vertical-align: middle;"><?=$model->game_title;?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;"><?=$model->total_unit;?></td>
                <td style="vertical-align: middle;"><?=$model->quantity;?></td>
                
                <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td style="vertical-align: middle;"><?=$model->getStatusLabel();?></td>
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;">
                  <a href='<?=Url::to(['order/verifying', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <a href='<?=Url::to(['order/delete', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-trash"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".ajax-link").ajax_action({
  method: 'POST',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});

// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa đơn hàng này không?',
  callback: function(data) {
    location.reload();
  },
  error: function(element, errors) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>