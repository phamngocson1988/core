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
$showCustomer = $user->can('saler') || $user->can('accounting');
?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Đơn hàng đã hoàn thành</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng đã hoàn thành</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng đã hoàn thành</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['order/completed'])]);?>
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
            ])->label('Ngày hoàn thành từ');?>

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
            ])->label('Ngày hoàn thành đến');?>

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
                <th col-tag="quantity"> Số gói </th>
                <th col-tag="created_at"> Thời điểm tạo </th>
                <th col-tag="completed_at"> Thời điểm hoàn thành </th>
                

                <th col-tag="completed_time"> Tổng TG hoàn thành </th>
                <th col-tag="supplier_completed_time"> Tổng TG NCC hoàn thành </th>
                <th col-tag="pending_time">TG duyệt</th>
                <th col-tag="distributed_time">TG phân phối</th>
                <th col-tag="approved_time">TG nhận đơn</th>
                <th col-tag="supplier_pending_time">TG login</th>
                <th col-tag="processing_time">TG nạp</th>
                
                <th col-tag="saler"> Người bán hàng </th>
                <th col-tag="orderteam"> Nhân viên đơn hàng </th>
                <th col-tag="status"> Trạng thái </th>
                <th col-tag="supplier"> Nhà cung cấp </th>
                <th col-tag="action" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="15" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td col-tag="id"><a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                <td col-tag="customer"><?=$model->getCustomerName();?></td>
                <td col-tag="game"><?=$model->game_title;?></td>
                <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($model->quantity, 2);?></td>
                <td col-tag="created_at"> <?=$model->created_at;?> </td>
                <td col-tag="completed_at"> <?=$model->completed_at;?> </td>
                <td col-tag="completed_time" class="center"><?=number_format($model->completed_time);?></td>
                <td col-tag="supplier_completed_time" class="center"><?=number_format($model->supplier_completed_time);?></td>
                <td col-tag="pending_time" class="center"><?=number_format($model->pending_time);?></td>
                <td col-tag="distributed_time" class="center"><?=number_format($model->distributed_time);?></td>
                <td col-tag="approved_time" class="center"><?=number_format($model->approved_time);?></td>
                <td col-tag="supplier_pending_time" class="center"><?=number_format($model->supplier_pending_time);?></td>
                <td col-tag="processing_time" class="center"><?=number_format($model->processing_time);?></td>

                <td col-tag="saler"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td col-tag="orderteam"><?=($model->orderteam) ? $model->orderteam->name : '';?></td>
                <td col-tag="status">
                  <?=$model->getStatusLabel();?>
                </td>
                <td col-tag="supplier">
                  <?php
                  $supplier = ArrayHelper::getValue($suppliers, $model->supplier_id);
                  echo ($supplier) ? sprintf("%s", $supplier->getName()) : '';
                  ?>
                </td>
                <td col-tag="action">
                  <a href='<?=Url::to(['order/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <?php if (Yii::$app->user->can('saler') || Yii::$app->user->can('accounting')) :?>
                  <a href='<?=Url::to(['order/move-to-confirmed', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa move-to-confirm tooltips" data-pjax="0" data-container="body" data-original-title="Xác nhận đơn hàng"><i class="fa fa-check-circle"></i></a>
                  <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
            </tbody>
            <?php $summary = $search->getSummary();?>
            <?php if (count($summary)) : ?>
            <tfoot style="background-color: #999;">
              <td col-tag="id" class="center"><?=number_format($summary['count']);?></td>
              <td col-tag="customer"></td>
              <td col-tag="game"></td>
              <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($summary['quantity'], 2);?></td>
              <td col-tag="created_at"></td>
              <td col-tag="completed_at"></td>
              <td col-tag="completed_time" class="center"><?=number_format($summary['avg_completed_time']);?></td>
              <td col-tag="supplier_completed_time" class="center"><?=number_format($summary['avg_supplier_completed_time']);?></td>
              <td col-tag="pending_time" class="center"><?=number_format($summary['avg_pending_time']);?></td>
              <td col-tag="distributed_time" class="center"><?=number_format($summary['avg_distributed_time']);?></td>
              <td col-tag="approved_time" class="center"><?=number_format($summary['avg_approved_time']);?></td>
              <td col-tag="supplier_pending_time" class="center"><?=number_format($summary['avg_supplier_pending_time']);?></td>
              <td col-tag="processing_time" class="center"><?=number_format($summary['avg_processing_time']);?></td>
              <td col-tag="saler"></td>
              <td col-tag="orderteam"></td>
              <td col-tag="status"></td>
              <td col-tag="supplier"></td>
              <td col-tag="action"></td>
            </tfoot>
            <?php endif;?>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$hiddenColumns = [];
if (Yii::$app->user->isRole(['orderteam', 'orderteam_manager'])) array_push($hiddenColumns, 'customer', 'saler');
if (Yii::$app->user->isRole(['customer_support', 'saler', 'sale_manager'])) array_push($hiddenColumns, 'orderteam', 'supplier');
$hiddenColumnString = implode(',', $hiddenColumns);
$script = <<< JS
var hiddenColumns = '$hiddenColumnString';
initTable('#order-table', '#no-data', hiddenColumns);
$(".move-to-confirm").ajax_action({
  confirm: true,
  confirm_text: 'Xác nhận hoàn thành?',
  method: 'POST',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});
JS;
$this->registerJs($script);
?>