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
      <span>Yêu cầu đang chờ</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Những yêu cầu trở thành nhà bán hàng liên kết</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Những yêu cầu đang chờ</span>
        </div>
        <div class="actions">
          <div class="btn-group btn-group-devided">
            <a class="btn green" href="<?=Url::to(['affiliate/index']);?>">Tất cả nhà bán hàng liên kết</a>
          </div>
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
              <th style="width: 10%;"> Ngày gửi yêu cầu </th>
              <th style="width: 10%;"> Preferred IM </th>
              <th style="width: 10%;"> IM Account </th>
              <th style="width: 10%;"> Company </th>
              <th style="width: 10%;"> Channel </th>
              <th style="width: 10%;"> Channel Type </th>
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
                <td style="vertical-align: middle;"><?=sprintf("(%s) %s", $model->user->country_code, $model->user->phone);?></td>
                <td style="vertical-align: middle;"><?=$model->created_at;?></td>
                <td style="vertical-align: middle;"><?=$model->preferred_im;?></td>
                <td style="vertical-align: middle;"><?=$model->im_account;?></td>
                <td style="vertical-align: middle;"><?=$model->company;?></td>
                <td style="vertical-align: middle;"><?=$model->channel;?></td>
                <td style="vertical-align: middle;"><?=$model->channel_type;?></td>
                <td style="vertical-align: middle;">
                  <a href="<?=Url::to(['affiliate/downgrade', 'id' => $model->user_id]);?>" class="btn btn-sm default link-action tooltips" data-container="body" data-original-title="Không phê duyệt"><i class="fa fa-arrow-down"></i> Disapprove </a>
                  <a href="<?=Url::to(['affiliate/upgrade', 'id' => $model->user_id]);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Chấp nhận"><i class="fa fa-arrow-up"></i> Approve </a>
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