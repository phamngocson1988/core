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
use supplier\behaviors\OrderSupplierBehavior;


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
      <span>Đơn hàng đang được yêu cầu</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng đang được yêu cầu</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng đang được yêu cầu</span>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/waiting']]);?>
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
          
            <?= $form->field($search, 'start_date', [
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
            ])->label('Ngày yêu cầu từ');?>

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
            ])->label('Ngày yêu cầu đến');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable" id="order-table">
            <thead>
              <tr>
                <th col-tag="order_id"> Mã đơn hàng </th>
                <th col-tag="game_title"> Shop game </th>
                <th col-tag="unit"> Số lượng nạp </th>
                <th col-tag="quantity"> Số gói </th>
                <th col-tag="approved_time"> TG nhận đơn </th>
                <th col-tag="status"> Trạng thái </th>
                <th col-tag="action" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $model) :?>
                <tr>
                  <td col-tag="order_id" class="center" style="max-width: none">#<?=$model->order_id;?></a></td>
                  <td col-tag="game_title" class="center"><?=$model->getGameTitle();?></td>
                  <td col-tag="unit" class="center"><?=number_format($model->unit);?></td>
                  <td col-tag="quantity" class="center"><?=number_format($model->quantity, 2);?></td>
                  <td col-tag="approved_time" class="center"><?=number_format($model->approved_time);?></td>
                  <td col-tag="" class="center">
                    <span class="label label-warning">Đang yêu cầu</span>
                  </td>
                  <td col-tag="" class="center">
                    <!-- <a href='<?=Url::to(['order/accept', 'id' => $model->id]);?>' class="btn btn-xs blue ajax-link tooltips" data-pjax="0" data-container="body" data-original-title="Nhận xử lý đơn hàng"><i class="fa fa-check"></i></a> -->
                    <a href='#accept<?=$model->id;?>' class="btn btn-xs blue tooltips" data-pjax="0" data-toggle="modal" data-container="body" data-original-title="Nhận xử lý đơn hàng"><i class="fa fa-check"></i></a>
                    <a href='<?=Url::to(['order/reject', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa ajax-link tooltips" data-pjax="0" data-container="body" data-original-title="Từ chối đơn hàng"><i class="fa fa-times"></i></a>

                    <div class="modal fade" id="accept<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                      <div class="modal-dialog">
                        <div class="modal-content">
                          <div class="modal-header" style="border-bottom: none">
                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          </div>
                          <?= Html::beginForm(['order/accept', 'id' => $model->id], 'POST', ['class' => 'accept-form']); ?>
                          <input type="hidden" class="btn btn-default" name="action" >
                          <div class="modal-body"> 
                            <div class="row">
                              <div class="col-md-12" style="text-align: left">
                                + Chọn "nhận đơn" nếu bạn chưa xử lý đơn hàng.<br/>
                                + Chỉ chọn "<span style="color:red; font-weight: bold;">Bắt đầu xử lý</span>" khi bạn đã sẵn sàng xử lý đơn hàng.
                              </div>
                            </div>
                          </div>
                          <div class="modal-footer" style="background-color: #dcfcff">
                            <button type="submit" class="btn btn-default" name="approve">Nhận đơn</button>
                            <button type="submit" class="btn dark btn-outline" name="process">Bắt đầu xử lý</button>
                          </div>
                          <?= Html::endForm(); ?>
                        </div>
                        <!-- /.modal-content -->
                      </div>
                      <!-- /.modal-dialog -->
                    </div>
                  </td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot style="background-color: #999;">
              <td col-tag="order_id"><?=number_format($search->count());?></td>
              <td col-tag="game_title"></td>
              <td col-tag="unit"></td>
              <td col-tag="quantity" class="center"><?=number_format($search->getSumQuantity(), 2);?></td>
              <td col-tag="approved_time" class="center"><?=number_format($search->getAverageApprovedTime(), 1);?></td>
              <td col-tag="status"></td>
              <td col-tag="action"></td>
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
$script = <<< JS
$(".accept-form button").on('click', function() {
  console.log('click button', $( this ).attr('name'));
  $(".accept-form").find("input[name='action']").val($( this ).attr('name'));
});
$(".accept-form").on('submit', function() {
  console.log('submit', $( this ).serialize());
  $.ajax({
      url: $(this).attr('action'),
      type: 'POST',
      dataType : 'json',
      data: $( this ).serialize(),
      success: function (result, textStatus, jqXHR) {
        console.log(result);
        if (result.status) {
          if (result.data.action == "process") {
            window.location.href = result.data.link;
          } else {
            location.reload();
          }
        } else {
          alert(result.errors)
        }
      },
  });
  return false;
})
// $(".ajax-link").ajax_action({
//   method: 'POST',
//   confirm_text: 'Bạn có muốn thực hiện tác vụ này?',
//   confirm: true,
//   callback: function(eletement, data) {
//     location.reload();
//   },
//   error: function(element, errors) {
//     console.log(errors);
//     alert(errors);
//   }
// });
JS;
$this->registerJs($script);
?>