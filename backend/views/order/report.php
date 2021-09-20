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
      <span>Thống kê đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thống kê đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê đơn hàng</span>
        </div>
        <div class="actions">
        <a role="button" class="btn btn-warning" href="<?=Url::current(['mode' => 'export'])?>"><i class="fa fa-file-excel-o"></i> Export</a>

        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['order/report'])]);?>
        <div class="row margin-bottom-10">
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

            <?=$form->field($search, 'supplier_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'data' => $search->fetchSuppliers(),
              'options' => ['class' => 'form-control', 'name' => 'supplier_id'],
              'pluginOptions' => [
                'placeholder' => 'Nhà cung cấp',
                'allowClear' => true,
              ]
            ])->label('Nhà cung cấp')?>

            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'saler_id']
            ])->dropDownList($salerTeams, ['prompt' => 'Chọn nhân viên hỗ trợ'])->label('Nhân viên hỗ trợ');?>

            <?=$form->field($search, 'orderteam_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'orderteam_id'],
            ])->dropDownList($orderTeams, ['prompt' => 'Chọn nhân viên phân phối'])->label('NV phân phối');?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'status']
            ])->dropDownList([
                Order::STATUS_PENDING => 'Pending',
                Order::STATUS_PROCESSING => 'Processing',
                Order::STATUS_COMPLETED => 'Completed',
                Order::STATUS_CONFIRMED => 'Confirmed',
                Order::STATUS_CANCELLED => 'Cancelled',
            ])->label('Trạng thái');?>

            <?=$form->field($search, 'date_time_type', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'date_time_type'],
            ])->dropDownList($search->fetchDateTimeType())->label('Lọc theo mốc thời gian');?>

            <?= $form->field($search, 'start_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'start_date']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Thời điểm đầu');?>

            <?=$form->field($search, 'end_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'end_date']
            ])->widget(DateTimePicker::className(), [
                'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd hh:59',
                  'todayBtn' => true,
                  'minuteStep' => 1,
                  'endDate' => date('Y-m-d H:i'),
                  'minView' => '1'
                ],
            ])->label('Thời điểm cuối');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-search"></i> Tìm kiếm
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable" id="order-table">
            <thead>
              <tr>
                <th col-tag="id">Mã đơn hàng</th>
                <th col-tag="customer_name">Tên khách hàng</th>
                <th col-tag="reseller_level">Cấp bậc KH</th>
                <th col-tag="country">Quốc gia</th>
                <th col-tag="game_title">Shop game</th>
                <th col-tag="game_method">Phương thức nạp</th>
                <th col-tag="quantity">Số gói</th>
                <th col-tag="payment_method">Cổng thanh toán</th>
                <th col-tag="created_at">Thời điểm tạo</th>
                <th col-tag="approved_at">Thời điểm NCC nhận đơn</th>
                <th col-tag="supplier_completed_at">Thời điểm hoàn thành</th>
                <th col-tag="order_confirmed_at">Thời điểm xác nhận</th>
                <th col-tag="order_completed_time">Tổng TG Hoàn Thành</th>
                <th col-tag="supplier_completed_time">Tổng TG NCC hoàn thành</th>
                <th col-tag="approved_time">TG duyệt</th>
                <th col-tag="distributed_time">TG phân phối</th>
                <th col-tag="supplier_approved_time">TG nhận đơn</th>
                <th col-tag="supplier_pending_time">TG login</th>
                <th col-tag="supplier_processing_time">TG nạp</th>
                <th col-tag="supplier_confirmed_time">TG xác nhận</th>
                <th col-tag="status">Trạng thái</th>
                <th col-tag="is_wrong">Sai thông tin </th>
                <th col-tag="wrong_information">Nội dung sai thông tin</th>
                <th col-tag="saler_name">NV Hổ Trợ </th>
                <th col-tag="orderteam_name">NV Phân Phối  </th>
                <th col-tag="supplier_name">Nhà Cung Cấp</th>
                <th col-tag="price">Giá bán ( Kcoin )</th>
                <th col-tag="total_price">Giá đơn hàng ( Kcoin )</th>
                <th col-tag="total_fee">Phí phát sinh ( Kcoin )</th>
                <th col-tag="total_promotion">Khuyến mãi ( Kcoin )</th>
                <th col-tag="total_paid">KH thanh Toán ( Kcoin )</th>
                <th col-tag="total_received">Thực nhận ( Kcoin )</th>
                <th col-tag="promotion_code">Mã khuyến mãi</th>
                <th col-tag="exchange_rate">Tỷ giá ( VND/Kcoin )</th>
                <th col-tag="supplier_price">Giá mua ( VND ) </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="32" id="no-data"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                  <td col-tag="id"><?=$model['id'];?></td>
                  <td col-tag="customer_name"><?=$model['customer_name'];?></td>
                  <td col-tag="reseller_level"><?=$model['reseller_level'];?></td>
                  <td col-tag="country"><?=$model['country'];?></td>
                  <td col-tag="game_title"><?=$model['game_title'];?></td>
                  <td col-tag="game_method"><?=$model['game_method'];?></td>
                  <td col-tag="quantity"><?=$model['quantity'];?></td>
                  <td col-tag="payment_method"><?=$model['payment_method'];?></td>
                  <td col-tag="created_at"><?=$model['created_at'];?></td>
                  <td col-tag="approved_at"><?=$model['approved_at'];?></td>
                  <td col-tag="supplier_completed_at"><?=$model['supplier_completed_at'];?></td>
                  <td col-tag="order_confirmed_at"><?=$model['order_confirmed_at'];?></td>
                  <td col-tag="order_completed_time"><?=$model['order_completed_time'];?></td>
                  <td col-tag="supplier_completed_time"><?=$model['supplier_completed_time'];?></td>
                  <td col-tag="approved_time"><?=$model['approved_time'];?></td>
                  <td col-tag="distributed_time"><?=$model['distributed_time'];?></td>
                  <td col-tag="supplier_approved_time"><?=$model['supplier_approved_time'];?></td>
                  <td col-tag="supplier_pending_time"><?=$model['supplier_pending_time'];?></td>
                  <td col-tag="supplier_processing_time"><?=$model['supplier_processing_time'];?></td>
                  <td col-tag="supplier_confirmed_time"><?=$model['supplier_confirmed_time'];?></td>
                  <td col-tag="status"><?=$model['status'];?></td>
                  <td col-tag="is_wrong"><?=$model['is_wrong'];?></td>
                  <td col-tag="wrong_information"><?=$model['wrong_information'];?></td>
                  <td col-tag="saler_name"><?=$model['saler_name'];?></td>
                  <td col-tag="orderteam_name"><?=$model['orderteam_name'];?></td>
                  <td col-tag="supplier_name"><?=$model['supplier_name'];?></td>
                  <td col-tag="price"><?=$model['price'];?></td>
                  <td col-tag="total_price"><?=$model['total_price'];?></td>
                  <td col-tag="total_fee"><?=$model['total_fee'];?></td>
                  <td col-tag="total_promotion"><?=$model['total_promotion'];?></td>
                  <td col-tag="total_paid"><?=$model['total_paid'];?></td>
                  <td col-tag="total_received"><?=$model['total_received'];?></td>
                  <td col-tag="promotion_code"><?=$model['promotion_code'];?></td>
                  <td col-tag="exchange_rate"><?=$model['exchange_rate'];?></td>
                  <td col-tag="supplier_price"><?=$model['supplier_price'];?></td>
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
