<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\PaymentTransaction;

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
      <span>Giao dịch nạp tiền</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Giao dịch nạp tiền</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Giao dịch nạp tiền</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['payment-transaction/offline'])]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'id']
            ])->textInput()->label('Mã giao dịch');?>

            <?=$form->field($search, 'remark', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'remark']
            ])->textInput()->label('Remark');?>

            <?php $customer = $search->user;?>
            <?=$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $search->user->username, $search->user->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn khách hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Khách hàng')?>

            <?= $form->field($search, 'created_at_from', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_at_from', 'id' => 'created_at_from']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày tạo từ');?>

            <?=$form->field($search, 'created_at_to', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_at_to', 'id' => 'created_at_to']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd hh:59',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d H:i'),
                  'minView' => '1'
                ],
            ])->label('Ngày tạo đến');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
        <thead>
            <tr>
              <th>Mã giao dịch</th>
              <th>Ngày tạo</th>
              <th>Số tiền</th>
              <th>Phương thức thanh toán</th>
              <th>Remark</th>
              <th>Loại thanh toán</th>
              <th>Trạng thái</th>
              <th>Khách hàng</th>
              <th>Hóa đơn</th>
              <th>Thao tác</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) :?>
            <tr><td colspan="9">No data found</td></tr>
            <?php endif;?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td>T<?=$model->id;?></td>
              <td><?=$model->created_at;?></td>
              <td>$<?=number_format($model->total_price);?></td>
              <td><?=sprintf("%s (%s)", $model->payment_method, $model->user_ip);?></td>
              <td><?=$model->remark;?></td>
              <td><?=$model->payment_type;?></td>
              <td><?=$model->status;?></td>
              <td><?=sprintf("%s (#%s)", $model->user->name, $model->user->id);?></td>
              <td>
                <?php if ($model->evidence) : ?>
                <a href="<?=$model->evidence;?>" target="_blank">Xem</a>
                <?php endif;?>
              </td>
              <td>
                <a href='#confirm-pay<?=$model->id;?>' class="btn btn-xs blue tooltips" data-pjax="0" data-container="body" data-original-title="Xác nhận đã thanh toán" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                <a href='<?=Url::to(['payment-transaction/send-mail-offline-payment', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips send-mail" data-pjax="0" data-container="body" data-original-title="Send mail"><i class="fa fa-envelope"></i></a>
                <a class="btn btn-xs red tooltips link-action" href="<?=Url::to(['payment-transaction/move-to-trash', 'id' => $model->id]);?>" data-container="body" data-original-title="Cho vào thùng rác"><i class="fa fa-trash"></i></a>
                <div class="modal fade" id="confirm-pay<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                  <div class="modal-dialog" role="document">
                    <div class="modal-content">
                      <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Xác nhận giao dịch này đã được chuyển khoản</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                          <span aria-hidden="true">&times;</span>
                        </button>
                      </div>
                      <?php $form = ActiveForm::begin(['options' => ['class' => 'confirm-form'], 'action' => ['payment-transaction/pay-offline', 'id' => $model->id]]);?>
                      <div class="modal-body">
                        <p>Xác nhận thanh toán cho giao dịch mã số <?=$model->getId();?></p>
                        <p>Số tiền phải nạp: $ <?=number_format($model->total_price, 1);?></p>
                        <?php if ($model->total_fee) : ?>
                        <p>Phí dịch vụ: $ <?=number_format($model->total_fee, 1);?></p>
                        <?php endif;?>
                        <p>Tổng cộng: $ <?=number_format($model->total_price + $model->total_fee, 1);?></p>
                        <?=$form->field($model, 'payment_id', [
                          'labelOptions' => ['class' => 'col-form-label'],
                        ])->textInput()?>
                        <?=$form->field($model, 'status', [
                          'options' => ['tag' => false],
                          'inputOptions' => ['value' => PaymentTransaction::STATUS_COMPLETED],
                          'template' => '{input}'
                        ])->hiddenInput()?>
                      </div>
                      <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-primary" data-toggle="modal">Xác nhận</button>
                      </div>
                      <?php ActiveForm::end()?>
                    </div>
                  </div>
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
$('.link-action').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa giao dịch này không?',
  callback: function(data) {
    location.reload();
  },
});

// mail
$('.send-mail').ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Gửi mail đến khách hàng?',
  callback: function(data) {
    alert('Thành công');
  },
  error: function(element, errors) {
    location.reload();
  }
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