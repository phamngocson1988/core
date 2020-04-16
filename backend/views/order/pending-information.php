<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use backend\models\User;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use common\components\helpers\FormatConverter;
use backend\models\Order;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);


$orderTeams = $search->fetchOrderTeams();
$salerTeams = $search->fetchSalerTeams();
$user = Yii::$app->user;
$canSaler = $user->can('saler');
$canOrderTeam = $user->can('orderteam');
?>
<!-- jQuery Modal -->
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Đơn hàng chờ xử lý</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng chờ xử lý</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng chờ xử lý</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET']);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'id']
            ])->textInput()->label('Mã đơn hàng');?>

            <?=$form->field($search, 'supplier_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'supplier_id'],
            ])->dropDownList($search->fetchSuppliers(), ['prompt' => 'Chọn nhà cung cấp'])->label('Nhà cung cấp');?>

            <?php if ($canOrderTeam) :?>
            <?=$form->field($search, 'orderteam_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'orderteam_id'],
            ])->dropDownList($orderTeams, ['prompt' => 'Chọn nhân viên phân phối'])->label('NV phân phối');?>
            <?php endif;?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status']
            ])->dropDownList([
                Order::STATE_PENDING_CONFIRMATION => 'Incoming Message',
                Order::STATE_PENDING_INFORMATION => 'Outgoing Message',
                Order::STATUS_PENDING => 'Pending',
            ], ['prompt' => 'Trạng thái đơn hàng'])->label('Trạng thái');?>

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
          
            <?php if ($canSaler) : ?>
            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'saler_id'],
            ])->dropDownList($salerTeams, ['prompt' => 'Chọn nhân viên hỗ trợ'])->label('NV hỗ trợ');?>
            <?php endif;?>

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
                <th> Mã đơn hàng </th>
                <th> Khách hàng </th>
                <th> Tên game </th>
                <th> Số lượng </th>
                <th> Thời gian tạo </th>
                <th> Chờ phản hồi </th>
                <th> Trạng thái </th>
                <th <?=($canSaler) ? '' : "class='hide'";?>> NV hỗ trợ </th>
                <th <?=($canOrderTeam) ? '' : "class='hide'";?>> NV phân phối </th>
                <th> Nhà cung cấp </th>
                <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="10"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <?php $supplier = $model->supplier;?>
                <?php $label = $model->getStatusLabel(null); ?>
                <tr>
                  <td class="center"><a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                  <td class="center"><?=$model->customer_name;?></td>
                  <td class="center"><?=$model->game_title;?></td>
                  <td class="center"><?=$model->quantity;?></td>
                  <td class="center"><?=date('d/m/y H:i', strtotime($model->created_at));?></td>
                  <td class="center">
                    <?php 
                    $lastComplain = ArrayHelper::getValue($complains, $model->id); 
                    if (!$lastComplain) {
                      echo '--';
                    } else {
                      echo FormatConverter::countDuration(strtotime('now') - strtotime($lastComplain), 'h:i');
                    }
                    ?>
                  </td>
                  <td class="center">
                    <?php if ($model->state == Order::STATE_PENDING_INFORMATION) : ?>
                    <span class="label label-primary">Outgoing Message</span>
                    <?php elseif ($model->state == Order::STATE_PENDING_CONFIRMATION) : ?>
                    <span class="label label-success">Incoming Message</span>
                    <?php else : ?>
                    <span class="label label-default">Pending</span>
                    <?php endif;?>
                  </td>
                  <td class="center"><?=($model->saler) ? $model->saler->name : '--';?></td>
                  <td class="center"><?=($model->orderteam) ? $model->orderteam->name : '--';?></td>
                  <td class="center"><?=($supplier) ? sprintf("%s", $supplier->user->name) : '--';?></td>
                  <td class="center">
                    
                    <a href='<?=Url::to(['order/edit', 'id' => $model->id, '#' => 'complain']);?>' class="btn btn-xs blue tooltips" data-pjax="0" data-container="body" data-original-title="Chat" ><i class="fa fa-comments-o"></i></a>

                    <!-- Order team -->
                    <?php if ($canOrderTeam && ($user->id == $model->orderteam_id || !$model->orderteam_id)) :?>
                    <a href='#assign<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chọn nhân viên phân phối" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                    <div class="modal fade" id="assign<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Gửi đơn hàng cho nhân viên quản lý</h4>
                          </div>
                          <?= Html::beginForm(['order/assign', 'id' => $model->id], 'POST', ['class' => 'assign-form']); ?>
                          <div class="modal-body"> 
                            <div class="row">
                              <div class="col-md-12">
                                <?= Html::dropDownList('user_id', null, $orderTeams, [
                                  'class' => 'bs-select form-control',
                                ]) ?>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                          </div>
                          <?= Html::endForm(); ?>
                        </div>
                      </div>
                    </div>
                    <?php endif;?>

                    <!-- Saler team -->
                    <?php if ($canSaler && ($user->id == $model->saler_id || !$model->saler_id)) :?>
                    <a href='#assign-saler<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chọn nhân viên hỗ trợ" data-toggle="modal" ><i class="fa fa-headphones"></i></a>
                    <div class="modal fade" id="assign-saler<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                            <h4 class="modal-title">Gửi đơn hàng cho nhân viên hỗ trợ</h4>
                          </div>
                          <?= Html::beginForm(['order/assign-saler', 'id' => $model->id], 'POST', ['class' => 'assign-form']); ?>
                          <div class="modal-body"> 
                            <div class="row">
                              <div class="col-md-12">
                                <?= Html::dropDownList('user_id', null, $salerTeams, [
                                  'class' => 'bs-select form-control',
                                ]) ?>
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer">
                            <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
                            <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                          </div>
                          <?= Html::endForm(); ?>
                        </div>
                      </div>
                    </div>
                    <?php endif;?>

                    <!-- Assign to supplier -->
                    <?php if (!$supplier) :?>
                    <a href='<?=Url::to(['order/assign-supplier', 'id' => $model->id]);?>' data-target="#assign-supplier" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chuyển đến nhà cung cấp" data-toggle="modal" ><i class="fa fa-rocket"></i></a>
                    <?php endif;?> <!-- end if supplier -->

                    <!-- Remove supplier -->
                    <?php if ($supplier && $supplier->canBeTaken()) : ?>
                    <a href='<?=Url::to(['order/remove-supplier', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa ajax-link tooltips" data-pjax="0" data-container="body" data-original-title="Thu hồi đơn hàng"><i class="fa fa-user-times"></i></a>
                    <?php endif;?> <!-- end if canBeTaken -->
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

<div class="modal fade" id="assign-supplier" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
<?php
$script = <<< JS
$(".ajax-link").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có muốn thực hiện tác vụ này?',
  method: 'POST',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});

// assign
var sendForm = new AjaxFormSubmit({element: '.assign-form'});
sendForm.success = function (data, form) {
  location.reload();
}

// supplier
$(document).on('submit', 'body #assign-supplier', function(e) {
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