<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Cổng thanh toán</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Cổng thanh toán</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Cổng thanh toán</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['paygate/create']);?>" data-toggle="modal"><?=Yii::t('app', 'add_new')?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> <?=Yii::t('app', 'no');?> </th>
              <th>Tên cổng thanh toán </th>
              <th> Loại cổng </th>
              <th> Phí giao dịch </th>
              <th> Tiền tệ </th>
              <th> Trạng thái </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td class="center"><?=$model->id;?></td>
                <td class="left">
                  <img class="img-thumbnail" width="50px" height="50px" src="<?=$model->getImageUrl('50x50');?>">
                  <?=$model->name;?>
                </td>
                <td class="left"><?=$model->paygate_type;?></td>
                <td class="left"><?=sprintf("%s (%s)", $model->transfer_fee, $model->transfer_fee_type);?></td>
                <td class="left"><?=$model->currency;?></td>
                <td class="left"><?=$model->isActive() ? 'Active' : 'Disabled';?></td>
                <td class="left">
                  <a href='<?=Url::to(['paygate/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa tin này không?',
  callback: function(data) {
    location.reload();
  },
});
JS;
$this->registerJs($script);
?>