<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use supplier\models\SupplierWallet;
use supplier\components\datetimepicker\DateTimePicker;

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
      <span>Ví Kingcoin</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Ví Kingcoin</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Ví Kingcoin</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => Url::to(['wallet/index'])]);?>
        <div class="row margin-bottom-10">
            <?= $form->field($search, 'created_at_from', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_at_from', 'id' => 'created_at_from']
            ])->widget(DateTimePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd hh:00',
                'minuteStep' => 1,
                'endDate' => date('Y-m-d H:i'),
                'minView' => '1'
              ],
            ])->label('Ngày tạo từ');?>

            <?=$form->field($search, 'created_at_to', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'created_at_to', 'id' => 'created_at_to']
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
        <table class="table table-striped table-bordered table-hover table-checkable">
        <thead>
            <tr>
              <th>Mã giao dịch</th>
              <th>Ngày tạo</th>
              <th>Số tiền</th>
              <th>Mô tả</th>
              <th>Loại thanh toán</th>
              <th>Trạng thái</th>
            </tr>
          </thead>
          <tbody>
            <?php if (!$models) :?>
            <tr><td colspan="7">No data found</td></tr>
            <?php endif;?>
            <?php foreach ($models as $model) :?>
            <tr>
              <td><?=$model->id;?></td>
              <td><?=$model->created_at;?></td>
              <td><?=number_format($model->amount, 1);?></td>
              <td><?=$model->description;?></td>
              <td>
                <?php if ($model->type == SupplierWallet::TYPE_INPUT) :?>
                  Nạp tiền
                <?php else :?>
                  Rút tiền
                <?php endif;?>
              </td>
              <td><?=$model->status;?></td>
            </tr>
            <?php endforeach;?>
          </tbody>
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
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện xóa giao dịch này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>