<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use supplier\components\datetimepicker\DateTimePicker;
use supplier\models\User;
use common\components\helpers\FormatConverter;
use common\components\helpers\StringHelper;
use supplier\models\Order;
use supplier\models\OrderSupplier;


$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\supplier\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\supplier\assets\AppAsset']);

?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Đơn hàng đã nhận xử lý</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<!-- <h1 class="page-title">Đơn hàng đã nhận xử lý</h1> -->
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light">
      <!-- <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng đã nhận xử lý </span>
        </div>
      </div> -->
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/pending']]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'order_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'order_id']
            ])->textInput()->label('Mã đơn hàng');?>

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
          
            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status', 'onchange' => 'js:$(this).closest("form").submit();']
            ])->dropDownList([
                Order::STATE_PENDING_CONFIRMATION => 'Incoming Message',
                Order::STATE_PENDING_INFORMATION => 'Outgoing Message',
                OrderSupplier::STATUS_APPROVE => 'Pending',
            ], ['prompt' => 'Trạng thái đơn hàng'])->label('Trạng thái');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th col-tag="order_id"> Mã đơn hàng </th>
                <th col-tag="game_title"> Shop game </th>
                <th col-tag="unit"> Số lượng nạp </th>
                <th col-tag="quantity"> Số gói </th>
                <th col-tag="pending_time"> Tổng TG chờ </th>
                <th col-tag="approved_time"> TG nhận đơn </th>
                <th col-tag="login_time"> TG login </th>
                <th col-tag="processing_time"> TG xử lý yêu cầu </th>
                <th col-tag="replying_time"> TG chờ phản hồi </th>
                <th col-tag="status"> Trạng thái </th>
                <th col-tag="actions" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="11"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <?php $order = $model->order;?>
                <tr>
                  <td col-tag="order_id" class="center" style="max-width: none">
                    <!-- <a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->order_id;?></a> -->
                    <?= Html::beginForm(['order/move-to-processing', 'id' => $model->id], 'post', ['class' => 'move-processing-form']) ?>
                    <button type="submit" class="btn btn-link">#<?=$model->order_id;?></button>
                    <?= Html::endForm();?>
                  </td>
                  <td col-tag="game_title" class="center"><?=$model->getGameTitle();?></td>
                  <td col-tag="unit" class="center"><?=$model->unit;?></td>
                  <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($model->quantity, 2);?></td>
                  <td col-tag="pending_time" class="center"><?=number_format($model->pending_time);?></td>
                  <td col-tag="approved_time" class="center"><?=number_format($model->approved_time);?></td>
                  <td col-tag="login_time" class="center"><?=number_format($model->login_time);?></td>
                  <td col-tag="processing_time" class="center">
                    <?php 
                    $firstComplain = ArrayHelper::getValue($firstComplains, $model->order_id); 
                    if (!$firstComplain) {
                      echo '--';
                    } else {
                      $duration = strtotime('now') - strtotime($firstComplain);
                      echo FormatConverter::countDuration($duration, 'h:i');
                    }
                    ?>
                  </td>
                  <td col-tag="replying_time" class="center">
                    <?php 
                    $lastComplain = ArrayHelper::getValue($lastComplains, $model->order_id); 
                    if (!$lastComplain) {
                      echo '--';
                    } else {
                      $duration = strtotime('now') - strtotime($lastComplain);
                      echo FormatConverter::countDuration($duration, 'h:i');
                    }
                    ?>
                  </td>
                  
                  <td col-tag="status" class="center">
                    <?php if ($order->hasCancelRequest()) :?>
                    <span class="label label-danger">Có yêu cầu hủy</span>
                    <?php endif;?>
                    <?php if ($order->state == Order::STATE_PENDING_INFORMATION) : ?>
                    <span class="label label-primary">Outgoing Message</span>
                    <?php elseif ($order->state == Order::STATE_PENDING_CONFIRMATION) : ?>
                    <span class="label label-success">Incoming Message</span>
                    <?php else : ?>
                    <span class="label label-default">Pending</span>
                    <?php endif;?>
                  </td>
                  <td col-tag="actions" class="center">
                    <!-- <a href="<?=Url::to(['order/edit', 'id' => $model->id]);?>" class="btn btn-sm red tooltips" data-container="body" data-original-title="Bắt đầu xử lý"><i class="fa fa-arrow-up"></i> Bắt đầu xử lý </a> -->
                    <?= Html::beginForm(['order/move-to-processing', 'id' => $model->id], 'post', ['class' => 'move-processing-form']) ?>
                    <button type="submit" class="btn btn-sm red tooltips" data-container="body" data-original-title="Bắt đầu xử lý"><i class="fa fa-arrow-up"></i> Bắt đầu xử lý</button>
                    <?= Html::endForm();?>

                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <?php $summary = $search->getSummary();?>
            <tfoot style="background-color: #999;">
              <td col-tag="order_id"><?=number_format($summary['count']);?></td>
              <td col-tag="game_title"></td>
              <td col-tag="unit"></td>
              <td col-tag="quantity" class="center"><?=StringHelper::numberFormat($summary['quantity'], 2);?></td>
              
              <td col-tag="pending_time" class="center"><?=number_format($summary['pending_time']);?></td>
              <td col-tag="approved_time" class="center"><?=number_format($summary['approved_time']);?></td>
              <td col-tag="login_time" class="center"><?=number_format($summary['login_time']);?></td>
              <td col-tag="processing_time" class="center"></td>
              <td col-tag="replying_time" class="center"></td>
              <td col-tag="status"></td>
              <td col-tag="actions"></td>
            </tfoot>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$moveProcessingJs = <<< JS
// supplier
$(document).on('submit', 'body .move-processing-form', function(e) {
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
      console.log(result);
      if (!result.status)
       alert(result.errors);
      else 
        window.location.href = result.editUrl;
    }
  });
  return false;
});
JS;
$this->registerJs($moveProcessingJs)
?>