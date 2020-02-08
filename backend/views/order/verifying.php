<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
?>
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
        <?php $form = ActiveForm::begin(['method' => 'GET']);?>
        <div class="row margin-bottom-10">
            <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput()->label('Mã đơn hàng');?>

            <?=$form->field($search, 'customer_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->customer_id) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'customer_id'],
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

            <?php if (Yii::$app->user->can('admin')) :?>
            <?php $saler = $search->getSaler();?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->saler_id) ? sprintf("%s - %s", $saler->username, $saler->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'saler_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên sale',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên sale')?>

            <?php $orderTeam = $search->getOrderteam();?>
            <?=$form->field($search, 'orderteam_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($orderTeam) ? sprintf("%s - %s", $orderTeam->username, $orderTeam->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'orderteam_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên đơn hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên đơn hàng')?>
            <?php elseif (Yii::$app->user->can('saler')):?>
              <?=$form->field($search, 'saler_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'saler_id']
              ])->hiddenInput()->label(false);?>
            <?php elseif (Yii::$app->user->can('orderteam')):?>
              <?=$form->field($search, 'orderteam_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'orderteam_id']
              ])->hiddenInput()->label(false);?>
            <?php endif;?>

            <?php $game = $search->getGame();?>   
            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($game) ? $game->title : '',
              'options' => ['class' => 'form-control', 'name' => 'game_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn game',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['game/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Tên game')?>
          
            <?= $form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'start_date', 'id' => 'start_date']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày tạo từ');?>

            <?=$form->field($search, 'end_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'end_date', 'id' => 'end_date']
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

            <?=$form->field($search, 'payment_method', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'payment_method']
            ])->dropDownList($search->fetchPaymentMethods(), ['prompt' => 'Chọn phương thức thanh toán'])->label('Phương thức thanh toán');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th> Mã đơn hàng </th>
                <th> Tên game </th>
                <th> Ngày tạo </th>
                <th> Số lượng nạp </th>
                <th> Số gói </th>
                <th> Tổng tiền </th>
                <th> Người bán hàng </th>
                <th> Trạng thái </th>
                <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="10"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                  <td style="vertical-align: middle; max-width:none"><a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                  <td style="vertical-align: middle;"><?=$model->game_title;?></td>
                  <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                  <td style="vertical-align: middle;"><?=$model->total_unit;?></td>
                  <td style="vertical-align: middle;"><?=$model->quantity;?></td>
                  <td style="vertical-align: middle;"><?=number_format($model->total_price, 1);?></td>
                  <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                  <td style="vertical-align: middle;"><?=$model->getStatusLabel();?></td>
                  <td style="vertical-align: middle;">
                    <a href='<?=Url::to(['order/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                    <?php if (Yii::$app->user->can('admin')) :?>
                    <a href='<?=Url::to(['order/delete', 'id' => $model->id]);?>' class="btn btn-xs red tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-trash"></i></a>
                    <?php endif;?>
                    <?php if ($model->evidence): ?>
                    <a href='<?=$model->evidence;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Xem hóa đơn" target="_blank"><i class="fa fa-file"></i></a>
                    <?php endif;?>
                    <a href='<?=Url::to(['order/send-mail-verifying-order', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips send-mail" data-pjax="0" data-container="body" data-original-title="Send mail"><i class="fa fa-envelope"></i></a>

                    <a href='#go_pending<?=$model->id;?>' class="btn btn-xs blue tooltips approve" data-pjax="0" data-container="body" data-original-title="Duyệt thanh toán" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                    <div class="modal fade" id="go_pending<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Chuyển tới trạng thái Pending</h4>
                          </div>
                          <?php $nextForm = ActiveForm::begin(['options' => ['class' => 'form-row-seperated move-to-pending', 'order-id' => $model->id], 'action' => Url::to(['order/move-to-pending', 'id' => $model->id])]);?>
                          <div class="modal-body"> 
                            <p>Bạn có chắc chắn muốn chuyển đơn hàng <strong>#<?=$model->id;?></strong> sang trạng thái "Pending". Hãy chắc chắn rằng đơn hàng này đã được thanh toán</p>
                            <p>Số tiền phải nạp: $ <?=number_format($model->total_price, 1);?></p>
                            <?php if ($model->total_fee) : ?>
                            <p>Phí dịch vụ: $ <?=number_format($model->total_fee, 1);?></p>
                            <?php endif;?>
                            <p>Tổng cộng: $ <?=number_format($model->total_price + $model->total_fee, 1);?></p>
                            <?=$nextForm->field($model, 'payment_method', ['inputOptions' => ['class' => 'form-control', 'id' => 'payment_method' . $model->id]])->textInput();?>
                            <?=$nextForm->field($model, 'payment_id', ['inputOptions' => ['class' => 'form-control', 'id' => 'payment_id' . $model->id]])->textInput();?>
                          </div>
                          <div class="modal-footer">
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                            <button type="submit" class="btn green">Xác nhận</button>
                          </div>
                          <?php ActiveForm::end();?>
                        </div>
                      </div>
                    </div>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
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
  confirm_text: 'Bạn có muốn xóa đơn hàng này không?',
  callback: function(data) {
    location.reload();
  },
  error: function(element, errors) {
    location.reload();
  }
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

// move to pending
var executeForm = new AjaxFormSubmit({element: '.move-to-pending'});
executeForm.success = function (data, form) {
  location.reload();
};

executeForm.validate = function(form) {
  var id = $(form).attr('order-id');
  var payment_method = $.trim($(form).find('#payment_method' + id).val());
  var payment_id = $.trim($(form).find('#payment_id' + id).val());
  if (payment_method == '' || payment_id == '') {
    return false;
  }
  return true;
}

executeForm.invalid = function(form) {
  alert('Nội dung không được để trống');
}
JS;
$this->registerJs($script);
?>