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

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);

$orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
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
      <span>Quản lý đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng</span>
        </div>
        <div class="actions">
          <?php if (Yii::$app->user->can('saler')) :?>
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['order/create', 'ref' => $ref])?>"><?=Yii::t('app', 'add_new')?></a>
          </div>
          <?php endif;?>
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/index']]);?>
        <div class="row margin-bottom-10">
            <?php $customer = $search->getCustomer();?>
            <?=$form->field($search, 'q', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'q']
            ])->textInput()->label('Mã đơn hàng');?>

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

            <?php if (Yii::$app->user->can('admin')) :?>
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

            <?php $orderTeam = $search->getOrderteam();?>
            <?=$form->field($search, 'orderteam_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($orderTeam) ? sprintf("%s - %s", $orderTeam->username, $orderTeam->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'orderteam_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn nhân viên đơn hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Nhân viên đơn hàng')?>
            <?php elseif (Yii::$app->user->can('saler')):?>
              <?=$form->field($search, 'saler_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'saler_id']
              ])->hiddenInput()->label(false);?>
            <?php elseif (Yii::$app->user->can('orderteam')):?>
              <?=$form->field($search, 'orderteam_id', [
                'template' => '{input}', 
                'options' => ['container' => false],
                'inputOptions' => ['name' => 'orderteam_id']
              ])->hiddenInput()->label(false);?>
            <?php endif;?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['multiple' => 'true', 'class' => 'bs-select form-control', 'name' => 'status[]']
            ])->dropDownList($search->getStatus())->label('Trạng thái');?>

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
          
            <?=$form->field($search, 'provider_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'bs-select form-control']
            ])->dropDownList([])->label('Nhà cung cấp');?>

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
              <th style="width: 5%;"> STT </th>
              <th style="width: 10%;"> Mã đơn hàng </th>
              <th style="width: 10%;"> Tên game </th>
              <th style="width: 5%;"> Số lượng nạp </th>
              <th style="width: 5%;"> Số gói </th>
              <th style="width: 10%;"> Thời gian nhận đơn </th>
              <th style="width: 5%;"> Thời gian chờ </th>
              <th style="width: 10%;"> Người bán hàng </th>
              <th style="width: 10%;"> Người quản lý đơn hàng </th>
              <th style="width: 10%;"> Trạng thái </th>
              <th style="width: 10%;"> Nhà cung cấp </th>
              <th style="width: 10%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="12"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td style="vertical-align: middle;"><?=$no + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                <td style="vertical-align: middle;"><?=$model->game_title;?></td>
                <td style="vertical-align: middle;"><?=$model->total_unit;?></td>
                <td style="vertical-align: middle;"><?=$model->quantity;?></td>
                <td style="vertical-align: middle;"><?=$model->process_start_time;?></td>
                <td style="vertical-align: middle;"><?=round($model->getProcessDurationTime() / 60, 1);?> minutes</td>
                
                <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td style="vertical-align: middle;"><?=($model->orderteam) ? $model->orderteam->name : '';?></td>
                <td style="vertical-align: middle;">
                  <?=$model->getStatusLabel();?>
                  <?php if ($model->hasCancelRequest()) :?>
                  <span class="label label-danger">Có yêu cầu hủy</span>
                  <?php endif;?>
                  <?php if ($model->tooLongProcess()) :?>
                  <span class="label label-warning">Xử lý chậm</span>
                  <?php endif;?>
                </td>
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;">
                  <?php if (Yii::$app->user->can('edit_order', ['order' => $model])) :?>
                  <?php switch ($model->status) {
                    case Order::STATUS_VERIFYING :
                      $editUrl = Url::to(['order/view', 'id' => $model->id]);
                      break;
                    case Order::STATUS_PENDING :
                      $editUrl = Url::to(['order/pending', 'id' => $model->id]);
                      break;
                    case Order::STATUS_PROCESSING :
                      $editUrl = Url::to(['order/processing', 'id' => $model->id]);
                      break;
                    default:
                      $editUrl = Url::to(['order/view', 'id' => $model->id]);
                      break;
                  };?>
                  <a href='<?=$editUrl;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <?php endif;?>
                  <?php if (Yii::$app->user->can('taken_order', ['order' => $model])) :?>
                  <a href='<?=Url::to(['order/taken', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa ajax-link tooltips" data-pjax="0" data-container="body" data-original-title="Nhận xử lý đơn hàng"><i class="fa fa-cogs"></i></a>
                  <?php endif;?>
                  <?php if (Yii::$app->user->can('delete_order', ['order' => $model])) :?>
                  <a href='<?=Url::to(['order/delete', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Xoá"><i class="fa fa-trash"></i></a>
                  <?php endif;?>
                  <?php if (Yii::$app->user->can('admin') && !$model->isDeletedOrder()) :?>
                  <a href='#assign<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips hide" data-pjax="0" data-container="body" data-original-title="Gán quyền xử lý" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
                  <div class="modal fade" id="assign<?=$model->id;?>" tabindex="-1" role="basic" aria-hidden="true">
                    <div class="modal-dialog">
                      <div class="modal-content">
                        <div class="modal-header">
                          <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
                          <h4 class="modal-title">Gửi đơn hàng cho nhân viên xử lý</h4>
                        </div>
                        <?= Html::beginForm(['order/assign', 'id' => $model->id], 'POST', ['class' => 'assign-form']); ?>
                        <div class="modal-body"> 
                          <div class="row">
                            <div class="col-md-12">
                              <?= kartik\select2\Select2::widget([
                                'name' => 'user_id',
                                'data' => $orderTeam,
                                'options' => ['placeholder' => 'Select user ...', 'class' => 'form-control'],
                              ]); ?>
                            </div>
                          </div>
                        </div>
                        <div class="modal-footer">
                          <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-send"></i> Gửi</button>
                          <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
                        </div>
                        <?= Html::endForm(); ?>
                      </div>
                      <!-- /.modal-content -->
                    </div>
                    <!-- /.modal-dialog -->
                  </div>
                  <?php endif;?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
        <?php if ($models) :?>
        <div class="row">
          <div class="col-md-2 col-sm-2">
            <span class="label label-danger">Tổng đơn hàng: <?=number_format($search->getCommand()->count());?></span>
          </div>
          <div class="col-md-2 col-sm-2">
            <span class="label label-warning">Tổng số lượng nạp: <?=number_format($search->getCommand()->sum('total_unit'));?></span>
          </div>
          <div class="col-md-2 col-sm-2">
            <span class="label label-success">Tổng số gói: <?=round($search->getCommand()->sum('quantity'), 1);?></span>
          </div>
          <div class="col-md-2 col-sm-2">
            <span class="label label-default">Thời gian trung bình: <?=round(($search->getCommand()->sum('process_duration_time') / 60) / $search->getCommand()->sum('quantity'), 1);?></span>
          </div>
        </div>
        <?php endif;?>
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