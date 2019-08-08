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
      <span>Yêu cầu rút hoa hồng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Yêu cầu rút hoa hồng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Yêu cầu rút hoa hồng</span>
        </div>
      </div>
      <div class="portlet-body">
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> STT </th>
              <th style="width: 10%;"> Tên </th>
              <th style="width: 10%;"> Email </th>
              <th style="width: 10%;"> Số điện thoại </th>
              <th style="width: 10%;"> Sô tiền </th>
              <th style="width: 10%;"> Ngày gửi yêu cầu </th>
              <th style="width: 10%;"> Ngày xử lý yêu cầu </th>
              <th style="width: 10%;"> Người phê duyệt </th>
              <th style="width: 10%;"> Người thực hiện </th>
              <th style="width: 10%;"> Ghi chú </th>
              <th style="width: 10%;"> Ảnh đính kèm </th>
              <th style="width: 5%;" class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="11"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td style="vertical-align: middle;"><?=$no + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><?=$model->user->name;?></td>
                <td style="vertical-align: middle;"><?=$model->user->email;?></td>
                <td style="vertical-align: middle;"><?=$model->user->phone;?></td>
                <td style="vertical-align: middle;"><?=number_format($model->amount);?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;"><?=$model->updated_at;?></td>
                <td style="vertical-align: middle;"><?=($model->acceptor) ? $model->acceptor->name : '';?></td>
                <td style="vertical-align: middle;"><?=($model->executor) ? $model->executor->name : '';?></td>
                <td style="vertical-align: middle;"><?=$model->note;?></td>
                <td style="vertical-align: middle;"><?=$model->evidence;?></td>
                <td style="vertical-align: middle;">
                  <?php if ($model->isRequest()) : ?>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'disapprove']);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Không phê duyệt"><i class="fa fa-arrow-down"></i> Disapprove </a>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'approve']);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Phê duyệt"><i class="fa fa-arrow-up"></i> Approve </a>
                  <?php elseif ($model->isApprove()) : ?>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'disapprove']);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Không phê duyệt"><i class="fa fa-arrow-down"></i> Disapprove </a>
                  <a href="<?=Url::to(['affiliate/execute-withdraw', 'id' => $model->id, 'action' => 'execute']);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Thực thi"><i class="fa fa-arrow-up"></i> Execute </a>
                  <?php elseif ($model->isExecuted()) : ?>
                  <span class="label label-default">Completed</span>
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
$(".link-action").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thực hiện tác vụ này?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>