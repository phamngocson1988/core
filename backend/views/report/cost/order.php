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

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$settings = Yii::$app->settings;
$rate = (int)$settings->get('ApplicationSettingForm', 'exchange_rate', 22000);
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
      <span>Thống kê & báo cáo</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê chi phí lợi nhuận</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Theo đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê theo đơn hàng</span>
        </div>
        <div class="actions">
          <a role="button" class="btn btn-success" href="<?=Url::to(['report/cost-order-statistics', 'start_date' => $search->start_date, 'end_date' => $search->end_date, 'period' => 'day']);?>"><i class="fa fa-bar-chart"></i> Biểu đồ</a>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/cost-order']]);?>
        <div class="row">
          <?=$form->field($search, 'q', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'q']
          ])->textInput()->label('Mã đơn hàng');?>

          <?php $customer = $search->getCustomer();?>
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

          <?=$form->field($search, 'is_reseller', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'is_reseller']
          ])->dropDownList(User::getResellerStatus(), ['prompt' => 'Tìm theo reseller'])->label('Reseller');?>

          <?=$form->field($search, 'agency_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'agency_id']
          ])->dropDownList($search->fetchGames(), ['prompt' => 'Tìm theo đại lý'])->label('Tên đại lý');?>

          <?=$form->field($search, 'provider_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'provider_id']
          ])->dropDownList($search->fetchGames(), ['prompt' => 'Tìm theo nhà cung cấp'])->label('Tên nhà cung cấp');?>

          <?=$form->field($search, 'start_date', [
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
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
          <thead>
            <tr>
              <th style="width: 2%;"> STT </th>
              <th style="width: 8%;"> Mã đơn hàng </th>
              <th style="width: 15%;"> Khách hàng / Reseller </th>
              <th style="width: 15%;"> Nhân viên sale/đại lý </th>
              <th style="width: 15%;"> Tên game </th>
              <th style="width: 5%;"> Số gói </th>
              <th style="width: 5%;"> Gía bán (VNĐ/gói) </th>
              <th style="width: 5%;"> Thành tiền (VNĐ) </th>
              <th style="width: 15%;"> Nhà cung cấp </th>
              <th style="width: 5%;"> Giá mua (VNĐ/ gói) </th>
              <th style="width: 5%;"> Thành tiền </th>
              <th style="width: 5%;"> Lợi nhuận </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="12"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td style="vertical-align: middle;"><?=$no + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>'><?=$model->id;?></a></td>
                <td style="vertical-align: middle;"><?=$model->customer->name;?></td>
                <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td style="vertical-align: middle;"><?=$model->game_title;?></td>
                <td style="vertical-align: middle;"><?=$model->quantity;?></td>
                <td style="vertical-align: middle;"><?=number_format($model->sub_total_price * $rate);?></td>
                <td style="vertical-align: middle;"><?=number_format($model->total_price * $rate);?></td>
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;"></td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Tổng: <?=round($search->getCommand()->sum('quantity'), 1);?></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
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
JS;
$this->registerJs($script);
?>