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
      <span>Quản lý tài khoản ngân hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý tài khoản ngân hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Các tài khoản ngân hàng</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['bank/create']);?>"><?=Yii::t('app', 'add_new');?></a>
          </div>
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> Mã ngân hàng </th>
              <th> Tên ngân hàng </th>
              <th> Số tài khoản </th>
              <th> Tên tài khoản </th>
              <th> Tỉnh </th>
              <th> Thành phố </th>
              <th> Chi nhánh </th>
              <th> Xác nhận </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="9"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $model) :?>
              <?php $bank = $model->bank;?>
              <tr>
                <td><?=$bank->code;?></td>
                <td><?=$bank->short_name;?></td>
                <td><?=$model->account_number;?></td>
                <td><?=$model->account_name;?></td>
                <td><?=$model->province;?></td>
                <td><?=$model->city;?></td>
                <td><?=$model->branch;?></td>
                <td>
                <?php if ($model->isNotVerified()) :?>
                  <span class="badge badge-warning">Chưa xác minh</span> 
                <?php else : ?>
                  <span class="badge badge-success">Đã xác minh</span>
                <?php endif;?>
                </td>
                <td>
                  <a href="<?=Url::to(['bank/delete', 'id' => $model->id]);?>" class="btn btn-sm red delete tooltips" data-container="body" data-original-title="Xóa tài khoản"><i class="fa fa-times"></i> Xóa </a>
                  <?php if ($model->isNotVerified()) :?>
                  <a href="<?=Url::to(['bank/verify', 'id' => $model->id]);?>" data-target="#verify-bank-modal" data-toggle="modal" class="btn btn-sm green tooltips" data-container="body" data-original-title="Xác minh tài khoản"><i class="fa fa-check"></i> Xác minh </a>
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

<div class="modal fade modal-scroll" id="verify-bank-modal" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog portlet box">
    <div class="modal-content portlet-body">
    </div>
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

// verify
$(document).on('submit', 'body #verify-bank', function(e) {
  e.preventDefault();
  e.stopImmediatePropagation();
  var form = $(this);
  form.unbind('submit');
  $.ajax({
    url: form.attr('action'),
    type: form.attr('method'),
    dataType : 'json',
    data: form.serialize(),
    success: function (result, textStatus, jqXHR) {
      if (!result.status)
        toastr.error(result.error); 
      else {
        setTimeout(location.reload(), 1000);
        toastr.success('Thông tin ngân hàng đã được xác thực'); 
      }
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>