<?php
use yii\helpers\Url;
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Yêu cầu rút tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Yêu cầu rút tiền</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Các yêu cầu rút tiền</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['withdraw/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Mã yêu cầu </th>
              <th> Số tiền </th>
              <th> Thông tin tài khoản </th>
              <th> Ngày tạo </th>
              <th> Trạng thái </th>
              <th> Ghi chú </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <tr>
                <td><?=$model->id;?></td>
                <td><?=number_format($model->amount);?></td>
                <td><?=sprintf("(%s) %s - %s", $model->bank_code, $model->account_number, $model->account_name);?></td>
                <td><?=$model->created_at;?></td>
                <td><?=$model->getStatusLabel();?></td>
                <td><?=$model->note;?></td>
                <td>
                  <?php if ($model->isRequest()) :?>
                  <a href="<?=Url::to(['withdraw/cancel', 'id' => $model->id]);?>" class="btn btn-sm purple delete tooltips" data-container="body" data-original-title="Hủy yêu cầu"><i class="fa fa-times"></i> Hủy yêu cầu </a>
                  <?php endif;?>
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
  confirm_text: 'Bạn có muốn xóa tài khoản này không?',
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