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
use backend\models\OrderCommission;
use common\models\User;
use common\components\helpers\FormatConverter;
use dosamigos\chartjs\ChartJs;
use common\components\helpers\StringHelper;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$orderIds = ArrayHelper::getColumn($data, 'order_id');
$orderIds = array_unique($orderIds);
$sumQuantity = array_sum(ArrayHelper::getColumn($data, 'quantity'));
$sumCommission = array_sum(ArrayHelper::getColumn($data, 'user_commission'));
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê & báo cáo</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Theo hoa hồng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo hoa hồng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Chi tiết theo nhân viên</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true">
              <thead>
                <tr>
                  <th> Ngày thực hiện </th>
                  <th> Mô tả </th>
                  <th> Tên game </th>
                  <th> Số gói </th>
                  <th class="hide"> LNG thực tế </th>
                  <th class="hide"> LNG chuẩn </th>
                  <th> Số tiền </th>
                </tr>
              </thead>
              <tbody>
                <?php if (!count($data)) :?>
                <tr><td colspan="5"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($data as $no => $commission) :?>
                <?php $description = json_decode($commission['description'], true);?>
                <tr>
                  <td class="center"><?=$commission['created_at'];?></td>
                  <td class="center"> Mã đơn hàng <a href='<?=Url::to(['report-commission/order-detail', 'id' => $commission['order_id'], 'type' => $commission['commission_type'], 'role' => $commission['role']]);?>' data-target="#order-detail" class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chi tiết đơn hàng" data-toggle="modal" >#<?=$commission['order_id'];?></a></td>
                  <td class="left"> <?=ArrayHelper::getValue($description, 'game_title');?></td>
                  <td class="center"><?=StringHelper::numberFormat($commission['quantity'], 1);?></td>
                  <td class="center hide"><?=StringHelper::numberFormat(ArrayHelper::getValue($description, 'real_profit', 0), 0);?> ₫</td>
                  <td class="center hide"><?=StringHelper::numberFormat(ArrayHelper::getValue($description, 'expected_profit', 0), 0);?> ₫</td>
                  <td class="center"><?=StringHelper::numberFormat($commission['user_commission'], 0);?> ₫</td>
                </tr>
                <?php endforeach;?>
              </tbody>
              <tfoot style="background-color: #999;">
                <td class="center"></td>
                <td class="center">Total Orders: <?=count($orderIds);?></td>
                <td class="center"></td>
                <td class="center"><?=StringHelper::numberFormat($sumQuantity, 0);?></td>
                <td class="center hide"></td>
                <td class="center hide"></td>
                <td class="center"><?=StringHelper::numberFormat($sumCommission, 0);?></td>
              </tfoot>
            </table>
          </div>
        </div>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>

<div class="modal fade" id="order-detail" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
    </div>
  </div>
</div>

<?php
$script = <<< JS
$(".ajax-link").ajax_action({
  method: 'POST',
  callback: function(eletement, data) {
    location.reload();
  },
  error: function(element, errors) {
    console.log(errors);
    alert(errors);
  }
});

// delete
$('.delete').ajax_action({
  method: 'DELETE',
  confirm: true,
  confirm_text: 'Bạn có muốn xóa đơn hàng này không?',
  callback: function(data) {
    location.reload();
  },
});

var sendForm = new AjaxFormSubmit({element: '.assign-form'});
sendForm.success = function (data, form) {
  location.reload();
}




const footer = (tooltipItems) => {
  let sum = 0;

  tooltipItems.forEach(function(tooltipItem) {
    sum += tooltipItem.parsed.y;
  });
  return 'Sum: ' + sum;
};


JS;
$this->registerJs($script);
?>