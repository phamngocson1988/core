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

$orderTeamIds = Yii::$app->authManager->getUserIdsByRole('handler');
$adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
$orderTeamIds = array_merge($orderTeamIds, $adminTeamIds);
$orderTeamIds = array_unique($orderTeamIds);
$orderTeamObjects = User::findAll($orderTeamIds);
$orderTeam = ArrayHelper::map($orderTeamObjects, 'id', 'email');

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
      <span>Thống kê thực hiện đơn hàng</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Theo game</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Theo game</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Thống kê thực hiện đơn hàng theo game</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/process-game']]);?>
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

          <div class='form-group col-md-4 col-lg-3'>
            <label class='control-label'>Thống kê theo:</label>
            <div class="clearfix">
              <div class="btn-group" data-toggle="buttons">
                <label class="btn red <?=($search->period == 'day') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="day"> Ngày </label>
                <label class="btn red <?=($search->period == 'week') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="week"> Tuần </label>
                <label class="btn red <?=($search->period == 'month') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="month"> Tháng </label>
                <label class="btn red <?=($search->period == 'quarter') ? 'active' : '';?>"><input type="radio" class="toggle" name="period" value="quarter"> Quý </label>
              </div>
            </div>
          </div>

          <div class="form-group col-md-4 col-lg-3">
            <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
              <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
            </button>
          </div>
        </div>
        <?php ActiveForm::end()?>
        <?php Pjax::begin(); ?>
        <div class="row">
          <div class="col-md-6">
            <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
              <thead>
                <tr>
                  <th style="width: 20%;"> Ngày thống kê </th>
                  <th style="width: 30%;"> Tên game </th>
                  <th style="width: 20%;"> Số lượng gói </th>
                  <th style="width: 20%;"> Tỷ lệ hoàn thành </th>
                  <th style="width: 10%;"> Thời gian xử lý trung bình </th>
                </tr>
              </thead>
              <tbody>
                  <?php if (!$models) :?>
                  <tr><td colspan="5"><?=Yii::t('app', 'no_data_found');?></td></tr>
                  <?php endif;?>
                  <?php foreach ($models as $date => $records) :?>
                  <?php foreach (array_values($records) as $no => $model) :?>
                  <tr>
                    <?php if (!$no) :?>
                    <td rowspan="<?=count($records);?>"><?=$date;?></td>
                    <?php endif;?>
                    <td style="vertical-align: middle;"><?=$model['game_title'];?></td>
                    <td style="vertical-align: middle;"><?=round($model['game_pack'], 1);?></td>
                    <td style="vertical-align: middle;"><?=round($model['completed_rate']) . '%';?></td>
                    <td style="vertical-align: middle;"><?=round($model['avarage_time']) . ' (minutes)';?></td>
                  </tr>
                  <?php endforeach;?>
                  <?php endforeach;?>
              </tbody>
            </table>
          </div>
          <div class="col-md-6">
            
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
JS;
$this->registerJs($script);
?>