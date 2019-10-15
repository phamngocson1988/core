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

$orderTeamIds = Yii::$app->authManager->getUserIdsByRole('orderteam');
$orderTeamManagerIds = Yii::$app->authManager->getUserIdsByRole('orderteam_manager');
$adminTeamIds = Yii::$app->authManager->getUserIdsByRole('admin');
$orderTeamIds = array_merge($orderTeamIds, $orderTeamManagerIds, $adminTeamIds);
$orderTeamIds = array_unique($orderTeamIds);
$orderTeamObjects = User::findAll($orderTeamIds);
$orderTeams = ArrayHelper::map($orderTeamObjects, 'id', 'email');
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
      <span>Đơn hàng đang xử lý</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Đơn hàng đang xử lý</h1>
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
          <!-- <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['order/create', 'ref' => $ref])?>"><?=Yii::t('app', 'add_new')?></a>
          </div> -->
          <?php endif;?>
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable" data-sortable="true" data-url="<?=Url::to(['order/index']);?>">
          <thead>
            <tr>
              <th style="width: 5%;"> Mã đơn hàng </th>
              <th style="width: 10%;"> Tên game </th>
              <th style="width: 10%;"> Ngày tạo </th>
              <th style="width: 5%;"> Số lượng nạp </th>
              <th style="width: 5%;"> Số gói </th>
              <th style="width: 10%;"> Thời gian nhận đơn </th>
              <th style="width: 5%;"> Thời gian chờ </th>
              <th style="width: 10%;"> Người bán hàng </th>
              <th style="width: 10%;"> Nhân viên đơn hàng </th>
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
                <td style="vertical-align: middle;"><a href='<?=Url::to(['order/edit', 'id' => $model->id, 'ref' => $ref]);?>'>#<?=$model->id;?></a></td>
                <td style="vertical-align: middle;"><?=$model->game_title;?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
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
                  <?php if (!$model->isDeletedOrder()) :?>
                  <a href='<?=Url::to(['order/edit', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chỉnh sửa"><i class="fa fa-pencil"></i></a>
                  <?php endif;?>
                  <?php if (Yii::$app->user->can('orderteam')) :?>
                  <a href='<?=Url::to(['order/taken', 'id' => $model->id, 'ref' => $ref]);?>' class="btn btn-xs grey-salsa ajax-link tooltips" data-pjax="0" data-container="body" data-original-title="Nhận xử lý đơn hàng"><i class="fa fa-cogs"></i></a>
                  <?php endif;?>
                  <?php if (Yii::$app->user->can('orderteam_manager')) :?>
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
                                'data' => $orderTeams,
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
  error: function(element, errors) {
    location.reload();
  }
});

var sendForm = new AjaxFormSubmit({element: '.assign-form'});
sendForm.success = function (data, form) {
  location.reload();
}
JS;
$this->registerJs($script);
?>