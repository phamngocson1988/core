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
use common\components\helpers\StringHelper;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
// order team
$orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
$orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');
$orderTeamIds = array_merge($orderTeamIds, $orderTeamManagerIds, $adminTeamIds);
$orderTeamIds = array_unique($orderTeamIds);
$orderTeamObjects = User::findAll($orderTeamIds);
$orderTeams = ArrayHelper::map($orderTeamObjects, 'id', 'email');

// saler team
$salerTeamIds = Yii::$app->authManager->getUserIdsByRole('saler');
$salerTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('saler_manager');
$salerTeamIds = array_merge($salerTeamIds, $salerTeamManagerIds, $adminTeamIds);
$salerTeamIds = array_unique($salerTeamIds);
$salerTeamObjects = User::findAll($salerTeamIds);
$salerTeams = ArrayHelper::map($salerTeamObjects, 'id', 'email');

$user = Yii::$app->user;
$showSupplier = $user->can('orderteam') || $user->can('accounting');
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Đơn hàng hoàn thành một phần</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng hoàn thành một phần</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng hoàn thành một phần</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['order/partial'])]);?>
        <div class="row margin-bottom-10">
            <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'id']
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

            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'saler_id']
            ])->dropDownList($salerTeams, ['prompt' => 'Chọn nhân viên sale'])->label('Nhân viên sale');?>

            <?=$form->field($search, 'orderteam_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'orderteam_id']
            ])->dropDownList($orderTeams, ['prompt' => 'Chọn nhân viên đơn hàng'])->label('Nhân viên đơn hàng');?>

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
          
            <?php if ($showSupplier): ?>
            <?=$form->field($search, 'supplier_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'supplier_id'],
            ])->dropDownList($search->fetchSuppliers(), ['prompt' => 'Chọn nhà cung cấp'])->label('Nhà cung cấp');?>
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
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable hidden" id="order-table">
            <thead>
              <tr>
                <th col-tag="id"> Mã đơn hàng </th>
                <th col-tag="customer"> Tên khách hàng </th>
                <th col-tag="game"> Shop Game </th>
                <th col-tag="quantity"> Số gói đã nạp / còn lại </th>
                <th col-tag="price"> Giá vốn (Vnd) </th>
                <th col-tag="waiting_time"> Tổng TG chờ </th>
                <!-- <th col-tag="waiting_time"> Tổng TG chờ (P) </th> -->
                <!-- <th col-tag="waiting_time"> TG phân phối (P) </th> -->
                <th col-tag="approved_time"> TG nhận đơn (P) </th>
                <th col-tag="login_time"> TG TB login (P) </th>
                <th col-tag="processing_time"> TG nạp (P) </th>
                <th col-tag="saler"> Người bán hàng </th>
                <th col-tag="orderteam"> Nhân viên đơn hàng </th>
                <th col-tag="status"> Trạng thái </th>
                <th col-tag="supplier"> Nhà cung cấp </th>
                <th col-tag="action" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="12" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <?php $supplier = $model->supplier;?>
                <?php $label = $model->getStatusLabel(null); ?>
                <tr>
                  <td col-tag="id"><a href='<?=Url::to(['order/edit', 'id' => $model->id]);?>'>#<?=$model->id;?></a></td>
                  <td col-tag="customer"><?=$model->getCustomerName();?></td>
                  <td col-tag="game"><?=$model->game_title;?></td>
                  <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($model->doing_unit, 2);?> / <?=StringHelper::numberFormat($model->quantity, 2);?></td>
                  <td col-tag="price" class="center">
                    <?=StringHelper::numberFormat($model->doing_unit, 2);?> / <?=$supplier ? StringHelper::numberFormat($supplier->price, 2) : '-' ;?>
                  </td>
                  <td col-tag="waiting_time" class="center"><?=number_format($model->waiting_time);?></td>
                  <td col-tag="approved_time" class="center"><?=number_format($model->approved_time);?></td>
                  <td col-tag="login_time" class="center"><?=number_format($model->login_time);?></td>
                  <td col-tag="processing_time" class="center"><?=number_format($model->processing_time);?></td>
                  <td col-tag="saler"><?=($model->saler) ? $model->saler->name : '';?></td>
                  <td col-tag="orderteam"><?=($model->orderteam) ? $model->orderteam->name : '';?></td>
                  <td col-tag="status">
                    <?php if ($model->hasCancelRequest()) :?>
                    <span class="label label-danger">Có yêu cầu hủy</span>
                    <?php endif;?>
                    <?php if ($model->tooLongProcess()) :?>
                    <span class="label label-warning">Xử lý chậm</span>
                    <?php endif;?>

                    <?php if ($supplier) :?>
                      <?php if ($supplier->isRequest()) : ?>
                    <span class="label label-warning"><?=$label;?></span>
                      <?php else : ?>
                    <span class="label label-success"><?=$label;?></span>
                    <?php endif;?>
                    <?php else :?>
                      <?=$model->getStatusLabel();?>
                    <?php endif;?>
                  </td>
                  <td col-tag="supplier">
                    <?=($supplier) ? sprintf("%s", $supplier->user->name) : '';?>
                  </td>
                  <td>
                    <a href='<?=Url::to(['order/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>

                    <!-- Assign to supplier -->
                    <?php if (Yii::$app->user->can('orderteam')) :?>
                    <?php if (!$supplier) :?>
                    <a href='<?=Url::to(['order/assign-supplier', 'id' => $model->id]);?>' data-target="#assign-supplier" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chuyển đến nhà cung cấp" data-toggle="modal" ><i class="fa fa-rocket"></i></a>
                    <?php endif;?> <!-- end if supplier -->

                    <!-- Remove supplier -->
                    <?php if ($supplier && $supplier->canBeTaken()) : ?>
                    <a href='<?=Url::to(['order/remove-supplier', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa remove-supplier tooltips" data-pjax="0" data-container="body" data-original-title="Thu hồi đơn hàng"><i class="fa fa-user-times"></i></a>
                    <?php endif;?> <!-- end if canBeTaken -->
                    <?php endif;?> <!-- end if orderteam -->
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot style="background-color: #999;">
              <td col-tag="id" class="center"><?=number_format($search->count());?></td>
              <td col-tag="customer"></td>
              <td col-tag="game"></td>
              <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($search->getSumQuantity(), 2);?></td>
              <td col-tag="price"></td>
              <td col-tag="waiting_time" class="center"><?=number_format($search->getAverageWaitingTime());?></td>
              <td col-tag="approved_time" class="center"><?=number_format($search->getAverageApprovedTime());?></td>
              <td col-tag="login_time" class="center"><?=number_format($search->getAverageLoginTime());?></td>
              <td col-tag="processing_time" class="center"><?=number_format($search->getAverageProcessingTime());?></td>
              <td col-tag="saler"></td>
              <td col-tag="orderteam"></td>
              <td col-tag="status"></td>
              <td col-tag="supplier"></td>
              <td col-tag="action"></td>
            </tfoot>
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
$hiddenColumns = [];
if (Yii::$app->user->isRole(['orderteam', 'orderteam_manager'])) array_push($hiddenColumns, 'saler');
if (Yii::$app->user->isRole(['customer_support', 'saler', 'sale_manager'])) array_push($hiddenColumns, 'orderteam', 'supplier');
$hiddenColumnString = implode(',', $hiddenColumns);

$script = <<< JS
var hiddenColumns = '$hiddenColumnString';
initTable('#order-table', '#no-data', hiddenColumns);

$(".remove-supplier").ajax_action({
  method: 'POST',
  confirm: true,
  confirm_text: 'Đơn hàng này đang ở trong trạng thái processing, bạn cần liên hệ với nhà cung cấp trước khi hủy đơn',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, error) {
    console.log(error);
    alert(error);
  }
});
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