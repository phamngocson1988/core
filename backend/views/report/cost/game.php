<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datetimepicker\DateTimePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;
use dosamigos\chartjs\ChartJs;

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
      <span>Theo game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chi phí lợi nhuận theo game</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Biểu đồ</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/cost-game']]);?>
        <div class="row">
          <?=$form->field($search, 'limit', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'limit', 'id' => 'limit']
          ])->dropDownList($search->getLimitOptions())->label('Tùy chọn thống kê');?>


          <?=$form->field($search, 'game_id', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
          ])->widget(kartik\select2\Select2::classname(), [
            'initValueText' => ($search->game_id) ? sprintf("%s", $search->getGame()->title) : '',
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
          
          <?=$form->field($search, 'start_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'start_date']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd HH:ii',
                'minuteStep' => 1,
              ]
          ])->label('Ngày tạo từ');?>

          <?=$form->field($search, 'end_date', [
            'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            'inputOptions' => ['class' => 'form-control', 'name' => 'end_date']
          ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                  'autoclose' => true,
                  'format' => 'yyyy-mm-dd HH:ii',
                  'todayBtn' => true,
                  'minuteStep' => 1,
              ]
          ])->label('Ngày tạo đến');?>

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="row">
          <div class="col-md-6">
            <?php Pjax::begin(); ?>
            <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
              <thead>
                <tr>
                  <th style="width: 10%;"> STT </th>
                  <th style="width: 20%;"> Game </th>
                  <th style="width: 10%;"> Số gói </th>
                  <th style="width: 20%;"> Doanh thu (Nghìn đồng) </th>
                  <th style="width: 20%;"> Chi phí (Nghìn đồng) </th>
                  <th style="width: 20%;"> Lợi nhuận (Nghìn đồng) </th>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$models) :?>
                  <tr><td colspan="6"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach ($models as $no => $model) :?>
                  <tr>
                    <td style="vertical-align: middle;"><?=$no + 1;?></td>
                    <td style="vertical-align: middle;"><?=$model['game_title'];?></td>
                    <td style="vertical-align: middle;"><?=round($model['game_pack'], 1);?></td>
                    <td style="vertical-align: middle;"><?=number_format($model['total_price'] * $rate);?></td>
                    <td style="vertical-align: middle;"></td>
                    <td style="vertical-align: middle;"></td>
                  </tr>
                  <?php endforeach;?>
              </tbody>
              <tfoot>
                <tr>
                  <td></td>
                  <td><strong>Tổng:</strong></td>
                  <td><?=round($search->getCommand()->sum('game_pack'), 1);?></td>
                  <td><?=number_format($search->getCommand()->sum('total_price') * $rate);?></td>
                  <td></td>
                  <td></td>
                </tr>
              </tfoot>
            </table>
            <?php Pjax::end(); ?>
          </div>
          <div class="col-md-6">
          <?php
          $command = $search->getCommand();
          $game_packs = array_map(function($model) { 
            return round($model['game_pack'], 1);
          }, $models);
          $total_prices = array_map(function($model) { 
              $settings = Yii::$app->settings;
              $rate = (int)$settings->get('ApplicationSettingForm', 'exchange_rate', 22000);
              return $model['total_price'] * $rate;
            }, $models);
          $labels = array_column($models, 'game_title');
          $datasets = [
              [
                  'label' => "Số gói",
                  'backgroundColor' => "rgba(54,198,211,0.2)",
                  'borderColor' => "rgba(54,198,211,1)",
                  'pointBackgroundColor' => "rgba(54,198,211,1)",
                  'pointBorderColor' => "#fff",
                  'pointHoverBackgroundColor' => "#fff",
                  'pointHoverBorderColor' => "rgba(54,198,211,1)",
                  'data' => array_values($game_packs)
              ],
              [
                  'label' => "Doanh thu (Nghìn đồng)",
                  'backgroundColor' => "rgba(255,99,132,0.2)",
                  'borderColor' => "rgba(255,99,132,1)",
                  'pointBackgroundColor' => "rgba(255,99,132,1)",
                  'pointBorderColor' => "#fff",
                  'pointHoverBackgroundColor' => "#fff",
                  'pointHoverBorderColor' => "rgba(255,99,132,1)",
                  'data' => array_values($total_prices)
              ],
          ];
          echo ChartJs::widget([
              'type' => 'bar',
              'options' => [
                  'height' => 200,
                  'width' => 400
              ],
              'data' => [
                  'labels' => $labels,
                  'datasets' => $datasets
              ]
          ]);
          ?>
          </div>
        </div>
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