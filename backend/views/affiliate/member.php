<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datepicker\DatePicker;
use backend\models\Order;
use common\models\User;
use common\components\helpers\FormatConverter;
use backend\behaviors\UserAffiliateBehavior;
use backend\behaviors\UserCommissionBehavior;
use backend\models\UserCommissionWithdraw;

?>
<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Chi tiết giao dịch khách hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chi tiết giao dịch khách hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Chi tiết giao dịch khách hàng</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['affiliate/member', 'member_id' => $search->member_id]]);?>
        <div class="row margin-bottom-10">
            <?=$form->field($search, 'id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'id']
            ])->textInput()->label('Mã giao dịch');?>

            <?= $form->field($search, 'report_start_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'report_start_date', 'id' => 'report_start_date']
            ])->widget(DatePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
              ],
            ])->label('Thống kê từ ngày');?>

            <?=$form->field($search, 'report_end_date', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'report_end_date', 'id' => 'report_end_date']
            ])->widget(DatePicker::className(), [
              'clientOptions' => [
                'autoclose' => true,
                'format' => 'yyyy-mm-dd',
              ],
            ])->label('Thống kê đến ngày');?>

            <?=$form->field($search, 'status', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
              'inputOptions' => ['class' => 'form-control', 'name' => 'status']
            ])->dropDownList($search->getStatusList(), ['prompt' => 'Chọn trạng thái'])->label('Trạng thái');?>

            <div class="form-group col-md-4 col-lg-3">
              <button type="submit" class="btn btn-success table-group-action-submit" style="margin-top: 25px;">
                <i class="fa fa-check"></i> <?=Yii::t('app', 'search')?>
              </button>
            </div>
        </div>
        <?php ActiveForm::end()?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Mã đơn hàng </th>
              <th> Thời gian thực hiện </th>
              <th> Giá trị đơn hàng ($) </th>
              <th> Hoa hồng ($) </th>
              <th> Trạng thái hoa hồng </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <?php $order = $model->order;?>
              <tr>
                <td><?=$no + $pages->offset + 1;?></td>
                <td><?=$order->id;?></td>
                <td><?=FormatConverter::countDuration($order->getProcessDurationTime());?></td>
                <td><?=number_format($order->total_price, 1);?></td>
                <td><?=number_format($model->commission, 1);?></td>
                <td>
                  <?php if ($model->isPending()) : ?>
                    Pending
                  <?php endif ;?>
                  <?php if ($model->isReady()) : ?>
                    Ready
                  <?php endif ;?>
                  <?php if ($model->isWithdrawed()) : ?>
                    Withdrawed
                  <?php endif ;?>
                </td>
                <td>
                  <?php if (!$model->isWithdrawed()) : ?>
                  <a href='<?=Url::to(['affiliate/delete-commission', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips delete" data-pjax="0" data-container="body" data-original-title="Thu hồi hoa hồng"><i class="fa fa-close"></i></a>
                  <?php endif ;?>
                  <?php if ($model->isPending()) : ?>
                  <a href='<?=Url::to(['affiliate/ready-commission', 'id' => $model->id]);?>' class="btn btn-xs grey-salsa tooltips move-to-ready" data-pjax="0" data-container="body" data-original-title="Chuyển trạng thái sẵn sàng"><i class="fa fa-exchange"></i></a>
                  <?php endif ;?>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
<?php
$script = <<< JS
$(".delete").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn thu hồi hoa hồng? Tác vụ này không thể được phục hồi.',
  callback: function(eletement, data) {
    location.reload();
  }
});
$(".move-to-ready").ajax_action({
  confirm: true,
  confirm_text: 'Bạn có chắc muốn chuyển hoa hồng này sang trạng thái "Sẵn sàng"?',
  callback: function(eletement, data) {
    location.reload();
  }
});
JS;
$this->registerJs($script);
?>