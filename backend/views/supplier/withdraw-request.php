<?php
use yii\helpers\Url;
use yii\bootstrap\ActiveForm;
use yii\helpers\Html;
use backend\models\User;

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
      <a href="<?=Url::to(['supplier/index']);?>">Nhà cung cấp</a>
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
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['supplier/withdraw-request']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'status[]']
            ])->dropDownList($search->getStatusList())->label('Trạng thái');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th> Mã yêu cầu </th>
                <th> Nhà cung cấp </th>
                <th> Số tiền rút </th>
                <th> Số dư khả dụng </th>
                <!-- <th> Số dư đầu </th>
                <th> Số dư cuối </th> -->
                <th> Thông tin tài khoản </th>
                <th> Ngày tạo </th>
                <th> Hình ảnh </th>
                <th> Trạng thái </th>
                <th> Ghi chú </th>
                <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="8"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <?php $user = User::findOne($model->supplier_id);?>
                <tr>
                  <td><?=$model->getId();?></td>
                  <td><?=sprintf("%s (#%s)", $user->name, $user->id);?></td>
                  <td><?=number_format($model->amount);?></td>
                  <td>
                    <?=number_format($model->available_balance);?>
                  </td>
                  <td>
                    <?=sprintf("(%s) %s - %s", $model->bank_code, $model->account_number, $model->account_name);?>
                  </td>
                  <td><?=$model->created_at;?></td>
                  <td>
                    <?php if ($model->isDone()) : ?>
                    <?php if (!$model->evidence) : ?>
                    <?php $form = ActiveForm::begin([
                        'action' => ['supplier/evidence-withdraw', 'id' => $model->id],
                        'options' => ['enctype' => 'multipart/form-data', 'class' => 'upload-form']
                    ]); ?>
                    <?=Html::fileInput("evidence", null, ['class' => 'file_upload', 'id' => 'evidence' . $model->id, 'style' => 'display:none']);?>
                    <?=Html::a('Upload Receipt', 'javascript:;', ['class' => 'upload-link normal-link']);?>
                    <?php ActiveForm::end(); ?>
                    <?php else : ?>
                    <a href="<?=$model->evidence;?>" class="normal-link" target="_blank">Xem</a> | 
                    <a href="<?=Url::to(['supplier/remove-evidence-withdraw', 'id' => $model->id]);?>" class="normal-link remove-link">Xóa</a>
                    <?php endif;?>
                    <?php endif;?>
                  </td>
                  <td><?=$model->getStatusLabel();?></td>
                  <td><?=$model->note;?></td>
                  <td>
                    <?php if ($model->isRequest()) :?>
                    <a href="<?=Url::to(['supplier/cancel-withdraw', 'id' => $model->id]);?>" class="btn btn-sm purple tooltips" data-container="body" data-original-title="Hủy yêu cầu" data-toggle="modal" data-target="#cancel-withdraw"><i class="fa fa-times"></i> Hủy yêu cầu </a>

                    <a href="<?=Url::to(['supplier/approve-withdraw', 'id' => $model->id]);?>" class="btn btn-sm yellow tooltips action-link" data-container="body" data-original-title="Chấp nhận yêu cầu"><i class="fa fa-times"></i> Chấp nhận yêu cầu </a>
                    <?php endif;?>

                    <?php if ($model->isApprove()) :?>
                      <a href="<?=Url::to(['supplier/cancel-withdraw', 'id' => $model->id]);?>" data-target="#cancel-withdraw" class="btn btn-sm purple tooltips" data-container="body" data-original-title="Hủy yêu cầu" data-toggle="modal"><i class="fa fa-times"></i> Hủy yêu cầu </a>

                      <a href="<?=Url::to(['supplier/done-withdraw', 'id' => $model->id]);?>" class="btn btn-sm blue tooltips action-link" data-container="body" data-original-title="Hoàn thành yêu cầu"><i class="fa fa-times"></i> Hoàn thành yêu cầu </a>
                    <?php endif;?>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <?php if ($models) :?>
            <tfoot>
              <tr>
                <td></td>
                <td></td>
                <td><?=number_format($search->getCommand()->sum('amount'));?></td>
                <td colspan="7"></td>
              </tr>
            </tfoot>
            <?php endif;?>
          </table>
        </div>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<div class="modal fade" id="cancel-withdraw" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>
<?php
$script = <<< JS
// action-link
$('.action-link').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn thực hiện tác vụ này không?',
  callback: function(data) {
    location.reload();
  },
  error: function(element, errors) {
    alert(errors);
  }
});

// Upload evidence
$('.file_upload').on('change', function() {
  $(this).closest('form').submit();
});
$('.upload-link').on('click', function() {
  $(this).closest('form').find('.file_upload').trigger('click');
});
$('.remove-link').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Do you want to remove this receipt?',
  callback: function(eletement, data) {
    location.reload();
  }
});

// cancel
$(document).on('submit', 'body #cancel-withdraw-form', function(e) {
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
       alert(result.error);
      else 
        location.reload();
    },
  });
  return false;
});
JS;
$this->registerJs($script);
?>