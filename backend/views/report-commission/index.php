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
          <span class="caption-subject bold uppercase"> Thống kê theo hoa hồng và sellout</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report-commission/index']]);?>
        <div class="row">
        <?=$form->field($search, 'user_ids', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'user_ids[]']
            ])->dropDownList($search->fetchUsers())->label('Nhân viên');?>
          <?=$form->field($search, 'start_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'start_date', 'id' => 'start_date']
          ])->widget(DateTimePicker::className(), [
            'clientOptions' => [
              'autoclose' => true,
              'format' => 'yyyy-mm-dd',
              'minuteStep' => 1,
              'endDate' => date('Y-m-d'),
              'minView' => '2'
            ],
          ])->label('Ngày tạo từ');?>

          <?=$form->field($search, 'end_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'end_date', 'id' => 'end_date']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
                'todayBtn' => true,
                'minuteStep' => 1,
                'endDate' => date('Y-m-d'),
                'minView' => '2'
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
        
        <div class="row">
          <div class="col-md-12">
            <?php
            $dataByUser = $search->getCommissionByUser();
            $users = ArrayHelper::getColumn($dataByUser, 'name', []);
            $orderCommissions = ArrayHelper::getColumn($dataByUser, OrderCommission::COMMSSION_TYPE_ORDER, []);
            $selloutCommissions = ArrayHelper::getColumn($dataByUser, OrderCommission::COMMSSION_TYPE_SELLOUT, []);
            echo ChartJs::widget([
              'type' => 'bar',
              'options' => [
                'height' => 100,
              //   'plugins' => [
              //     'tooltip' => [
              //       'enabled' => true,
              //       'external' => new JsExpression(
              //         'function(context) {
              //           console.log(context);
              //         }'
              //       )
              //     ]
              //   ]
              ],
              'clientOptions' => [
                'events' => ['click'],
                'onClick' => new JsExpression(
                  "function(event, item) {
                    console.log('click', item);
                  }"
                ),
                'tooltips' => [
                  'enabled' => true,
                  'external' => new JsExpression(
                    "function(context) {
                      console.log(context);
                    }"
                  ),
                  'callbacks' => [
                    'label' => new JsExpression(
                      "function(label) {
                        return new Intl.NumberFormat('vi-VN', { style: 'currency', currency: 'VND' }).format(label.value);
                    }"
                    )
                  ]
                ],
                
              ],
              'data' => [
                'labels' => $users,
                'datasets' => [
                  [
                    'label' => "Hoa hồng",
                    'backgroundColor' => "rgba(54, 162, 235, 0.5)",
                    'borderColor' => "rgb(54, 162, 235)",
                    'borderWidth' => 2,
                    'borderRadius' => '10px',
                    'borderSkipped' => false,
                    'data' => $orderCommissions,
                    'stack' => 'user_id',
                  ],
                  [
                    'label' => "Sellout",
                    'backgroundColor' => "rgba(255, 99, 132, 0.5)",
                    'borderColor' => "rgb(255, 99, 132)",
                    'borderWidth' => 2,
                    'borderRadius' => '10px',
                    'data' => $selloutCommissions,
                    'stack' => 'user_id',
                  ],
                ]
              ]
            ]);
            ?>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
              <thead>
                <tr>
                  <th style="width: 30%;"> Nhân viên </th>
                  <th style="width: 20%;"> Loại hoa hồng </th>
                  <th style="width: 20%;"> Số tiền </th>
                </tr>
              </thead>
              <tbody>
                <?php $models = $search->getData();?>
                <?php if (!$models) :?>
                <tr><td colspan="3"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                  <td style="vertical-align: middle;"><?=$model['username'];?></td>
                  <td style="vertical-align: middle;"><?=$model['commission_type'];?></td>
                  <td style="vertical-align: middle;"><?=round($model['user_commission']);?></td>
                </tr>
                <?php endforeach;?>
              </tbody>
            </table>
          </div>
        </div>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
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