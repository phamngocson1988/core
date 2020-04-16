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
              'inputOptions' => ['class' => 'bs-select form-control', 'name' => 'status']
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
                <th> Mã đơn hàng </th>
                <th> Tên game </th>
                <th> Số gói </th>
                <th> Chờ nhận đơn </th>
                <th> Chờ login </th>
                <th> Chờ phản hồi </th>
                <th> Trạng thái </th>
                <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="8"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <?php $order = $model->order;?>
                <tr>
                  <td class="center" style="max-width: none">#<?=$model->order_id;?></td>
                  <td class="center"><?=$model->getGameTitle();?></td>
                  <td class="center"><?=$model->quantity;?></td>
                  <td class="center"><?=FormatConverter::countDuration(strtotime($model->approved_at) - strtotime($model->created_at), 'h:i');?></td>
                  <td class="center"><?=FormatConverter::countDuration(strtotime('now') - strtotime($model->approved_at), 'h:i');?></td>
                  <td class="center">
                    <?php 
                    $lastComplain = ArrayHelper::getValue($complains, $model->order_id); 
                    if (!$lastComplain) {
                      echo '--';
                    } else {
                      $duration = strtotime('now') - strtotime($lastComplain);
                      echo FormatConverter::countDuration($duration, 'h:i');
                    }
                    ?>
                  </td>
                  <td class="center">
                    <?php if ($order->state == Order::STATE_PENDING_INFORMATION) : ?>
                    <span class="label label-primary">Outgoing Message</span>
                    <?php elseif ($order->state == Order::STATE_PENDING_CONFIRMATION) : ?>
                    <span class="label label-success">Incoming Message</span>
                    <?php else : ?>
                    <span class="label label-default">Pending</span>
                    <?php endif;?>
                  </td>
                  <td class="center">
                    <a href="<?=Url::to(['order/edit', 'id' => $model->id]);?>" class="btn btn-sm red tooltips" data-container="body" data-original-title="Bắt đầu xử lý"><i class="fa fa-arrow-up"></i> Bắt đầu xử lý </a>
                  </td>
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