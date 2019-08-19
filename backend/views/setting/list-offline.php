<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;
use backend\models\PaymentTransaction;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
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
      <a href="javascript:;"><?=Yii::t('app', 'settings');?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Đơn hàng thanh toán chuyển khoản</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng thanh toán chuyển khoản</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng giao dịch chuyển khoản</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <form method="GET">
          <div class="form-group col-md-4">
            <label>Nhập mã giao dịch: </label> <input type="search" class="form-control" placeholder="Nhập mã giao dịch" name="auth_key" value="<?=$auth_key;?>">
          </div>
          <div class="form-group col-md-3">
            <button type="submit" class="btn btn-success table-group-action-submit"
              style="margin-top:
              25px;">
            <i class="fa fa-check"></i> Tìm kiếm
            </button>
          </div>
        </form>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
        <thead>
            <tr>
              <th>Mã giao dịch</th>
              <th>Ngày tạo</th>
              <th>Số tiền</th>
              <th>Phương thức thanh toán</th>
              <th>Trạng thái</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) :?>
            <tr><td colspan="6">No data found</td></tr>
            <?php endif;?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td><?=$model->auth_key;?></td>
              <td><?=$model->created_at;?></td>
              <td>$<?=number_format($model->total_price);?></td>
              <td><?=$model->payment_method;?></td>
              <td><?=$model->status;?></td>
              <td>
                <a href='#confirm-pay<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Xác nhận đã thanh toán" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                <a href='<?=Url::to(['setting/delete-offline', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xóa giao dịch" ><i class="fa fa-trash-o"></i></a>
                <div class="modal fade" id="confirm-pay<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                        <h4 class="modal-title">Xác nhận giao dịch này đã được chuyển khoản</h4>
                      </div>
                      <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated confirm-form'], 'action' => ['setting/pay-offline', 'id' => $model->id]]);?>
                      <div class="modal-body"> 
                        <div class="row">
                          <?=$form->field($model, 'payment_id', [
                            'labelOptions' => ['class' => 'col-md-2 control-label'],
                            'inputOptions' => ['id' => 'name', 'class' => 'form-control', 'value' => ''],
                            'template' => '{label}<div class="col-md-6">{input}{hint}{error}</div>'
                          ])->textInput()?>
                          <?=$form->field($model, 'status', [
                            'options' => ['tag' => false],
                            'inputOptions' => ['value' => PaymentTransaction::STATUS_COMPLETED],
                            'template' => '{input}'
                          ])->hiddenInput()?>
                          <div class="col-md-12">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer">
                        <button type="submit" class="btn btn-default" data-toggle="modal">Xác nhận</button>
                        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Đóng</button>
                      </div>
                      <?php ActiveForm::end()?>
                    </div>
                    <!-- /.modal-content -->
                  </div>
                  <!-- /.modal-dialog -->
                </div>
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
// delete
$('.delete').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa giao dịch này không? Nó sẽ không thể phục hồi',
  callback: function(data) {
    location.reload();
  },
});

var sendForm = new AjaxFormSubmit({element: '.confirm-form'});
sendForm.success = function (data, form) {
  location.reload();
}
sendForm.error = function (errors) {
  alert(errors);
  return false;
}
JS;
$this->registerJs($script);
?>