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
      <span>Đơn hàng đã được khách xác nhận</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng đã được khách xác nhận</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng đã được khách xác nhận</span>
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

            <?= $form->field($search, 'confirmed_from', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'confirmed_from', 'id' => 'confirmed_from']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày xác nhận từ');?>

            <?=$form->field($search, 'confirmed_to', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'confirmed_to', 'id' => 'confirmed_to']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd hh:59',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d H:i'),
                  'minView' => '1'
                ],
            ])->label('Ngày xác nhận đến');?>

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
          <table class="table table-bordered">
            <thead>
              <tr>
                <th> Mã đơn hàng </th>
                <th <?=$showCustomer ? '' : 'class="hide"';?>> Tên khách hàng </th>
                <th> Tên game </th>
                <th> Ngày xác nhận </th>
                <th <?=$showCustomer ? '' : 'class="hide"';?>> Cổng thanh toán </th>
                <th> Số lượng nạp </th>
                <th> Số gói </th>
                <th class="hidden-xs"> Thời gian nhận đơn </th>
                <th> Thời gian chờ </th>
                <th class="hidden-xs"> Người bán hàng </th>
                <th class="hidden-xs"> Nhân viên đơn hàng </th>
                <th> Trạng thái </th>
                <th <?=$showSupplier ? '' : 'class="hide"';?>> Nhà cung cấp </th>
                <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="14"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                  <td><a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                  <td <?=$showCustomer ? '' : 'class="hide"';?>><?=$model->getCustomerName();?></td>
                  <td><?=$model->game_title;?></td>
                  <td><?=$model->confirmed_at;?></td>
                  <td <?=$showCustomer ? '' : 'class="hide"';?>><?=$model->payment_method;?></td>
                  <td><?=$model->total_unit;?></td>
                  <td><?=$model->quantity;?></td>
                  <td class="hidden-xs"><?=$model->process_start_time;?></td>
                  <td><?=FormatConverter::countDuration($model->getProcessDurationTime());?></td>
                  <td class="hidden-xs"><?=($model->saler) ? $model->saler->name : '';?></td>
                  <td class="hidden-xs"><?=($model->orderteam) ? $model->orderteam->name : '';?></td>
                  <td>
                    <?=$model->getStatusLabel();?>
                    <?php if ($model->hasCancelRequest()) :?>
                    <span class="label label-danger">Có yêu cầu hủy</span>
                    <?php endif;?>
                    <?php if ($model->tooLongProcess()) :?>
                    <span class="label label-warning">Xử lý chậm</span>
                    <?php endif;?>
                  </td>
                  <td <?=$showSupplier ? '' : 'class="hide"';?>>
                    <?php
                    $suppliers = $model->suppliers;
                    foreach ($suppliers as $supplier) {
                      echo sprintf('%s (%s)<br/>', $supplier->user->name, $supplier->doing);
                    }
                    ?>
                  </td>
                  <td>
                    <a href='<?=Url::to(['order/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php if ($models) :?>
        <?php $sumQuantity = $search->getCommand()->sum('order.quantity');?>
        <?php if ($sumQuantity) : ?>
        <div class="row">
          <div class="col-md-2 col-sm-4">
            <span class="label label-danger">Tổng đơn hàng: <?=number_format($search->getCommand()->count());?></span>
          </div>
          <div class="col-md-2 col-sm-4">
            <span class="label label-success">Tổng số gói: <?=round($sumQuantity, 1);?></span>
          </div>
        </div>
        <?php endif;?>
        <?php endif;?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>