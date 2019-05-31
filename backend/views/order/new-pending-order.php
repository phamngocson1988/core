<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datepicker\DateRangePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerCssFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-daterangepicker/daterangepicker.min.js', ['depends' => '\backend\assets\AppAsset']);

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-table/bootstrap-table.min.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-table/bootstrap-table.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/table-bootstrap.min.js', ['depends' => '\backend\assets\AppAsset']);

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
      <span>Đơn hàng chưa có nhân viên xử lý</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng chưa có nhân viên xử lý</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase"> Đơn hàng chưa có nhân viên xử lý</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
      <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['order/new-pending-order']]);?>
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

            <?=$form->field($search, 'saler_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'saler_id']
            ])->dropDownList($search->fetchSalers(), ['prompt' => 'Tìm theo nhân viên bán hàng'])->label('Nhân viên bán hàng');?>

            <?=$form->field($search, 'game_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'game_id']
            ])->dropDownList($search->fetchGames(), ['prompt' => 'Tìm theo game'])->label('Tên game');?>

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
                <td style="vertical-align: middle;"><a href='<?=Url::to(['order/view', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->auth_key;?></a></td>
                <td style="vertical-align: middle;"><?=$model->game_title;?></td>
                <td style="vertical-align: middle;"><?=$model->total_unit;?></td>
                <td style="vertical-align: middle;"><?=$model->game_pack;?></td>
                <td style="vertical-align: middle;"><?=$model->process_start_time;?></td>
                <td style="vertical-align: middle;"><?=$model->process_duration_time;?></td>
                
                <td style="vertical-align: middle;"><?=($model->saler) ? $model->saler->name : '';?></td>
                <td style="vertical-align: middle;"><?=($model->handler) ? $model->handler->name : '';?></td>
                <td style="vertical-align: middle;"><?=$model->getStatusLabel();?></td>
                <td style="vertical-align: middle;"></td>
                <td style="vertical-align: middle;">
                  <?php if (Yii::$app->user->can('edit_order', ['order' => $model])) :?>
                  <?php switch ($model->status) {
                    case Order::STATUS_VERIFYING :
                      $editUrl = Url::to(['order/verifying', 'id' => $model->id]);
                      break;
                    case Order::STATUS_PENDING :
                      $editUrl = Url::to(['order/pending', 'id' => $model->id]);
                      break;
                    case Order::STATUS_PROCESSING :
                      $editUrl = Url::to(['order/processing', 'id' => $model->id]);
                      break;
                    
                    default:
                      $editUrl = '';
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
                  <?php if (Yii::$app->user->can('admin')) :?>
                  <a href='#assign<?=$model->id;?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Gán quyền xử lý" data-toggle="modal" ><i class="fa fa-exchange"></i></a>
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
JS;
$this->registerJs($script);
?>