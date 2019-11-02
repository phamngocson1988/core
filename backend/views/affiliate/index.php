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
      <span>Quản lý nhà bán hàng liên kết</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Quản lý nhà bán hàng liên kết</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Nhà bán hàng liên kết</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['affiliate/index']]);?>
        <div class="row margin-bottom-10">
            <?php $customer = $search->getUser();?>
            <?=$form->field($search, 'user_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->user_id) ? sprintf("%s - %s", $customer->username, $customer->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'user_id'],
              'pluginOptions' => [
                'placeholder' => 'Chọn affiliate',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Tìm theo affiliate')?>

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
              <th> Tên </th>
              <th> Email </th>
              <th> Thành viên </th>
              <th> Doanh số (số gói) </th>
              <th> Hoa hồng tích lũy </th>
              <th> Hoa hồng khả dụng </th>
              <th> Tổng số tiền rút </th>
              <th> Tổng hoa hồng </th>
              <th class="dt-center"> <?=Yii::t('app', 'actions');?> </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="12"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <?php $user = $model->user;?>
              <?php $user->attachBehavior('commission', UserCommissionBehavior::className());?>
              <?php $user->attachBehavior('affiliate', UserAffiliateBehavior::className());?>
              <?php 
              $commission = $user->getCommission();
              // pending
              $pending = clone $commission;
              if ($search->report_start_date) {
                  $pending->andWhere(['>=', 'created_at', $search->report_start_date]);
              }
              if ($search->report_end_date) {
                  $pending->andWhere(['<=', 'created_at', $search->report_end_date]);
              }
              $quantities = array_map(function($c) {
                $order = $c->order;
                return $order->quantity;
              }, $pending->all());
              $orderQuantities = number_format(array_sum($quantities), 1);
              $pendingCommission = number_format($pending->sum('commission'), 1);

              // available 
              $available = clone $commission;
              if ($search->report_start_date) {
                  $available->andWhere(['>=', 'valid_from_date', $search->report_start_date]);
              } 
              if ($search->report_end_date) {
                  $available->andWhere(['<=', 'valid_from_date', $search->report_end_date]);
              } else {
                  $available->andWhere(['<=', 'valid_from_date', date('Y-m-d')]);
              }
              $availableCommission = number_format($available->sum('commission'), 1);
              ?>
              <?php
              $withdraw = $user->getCommissionWithdraw();
              $withdraw->where(['status' => UserCommissionWithdraw::STATUS_EXECUTED]);
              if ($search->report_start_date) {
                  $withdraw->andWhere(['>=', 'executed_at', $search->report_start_date . ' 00:00:00']);
              }
              if ($search->report_end_date) {
                  $withdraw->andWhere(['<=', 'executed_at', $search->report_end_date . ' 23:59:59']);
              }
              $withdrawAmount = number_format($withdraw->sum('amount'), 1);
              ?>
              <tr>
                <td style="vertical-align: middle;"><?=$no + $pages->offset + 1;?></td>
                <td style="vertical-align: middle;"><?=$user->name;?></td>
                <td style="vertical-align: middle;"><?=$user->email;?></td>
                <td style="vertical-align: middle;"><?=number_format($user->getAffiliateMembers()->count());?></td>
                <td style="vertical-align: middle;"><?=$orderQuantities;?></td>
                <td style="vertical-align: middle;"><?=$pendingCommission;?></td>
                <td style="vertical-align: middle;"><?=$availableCommission;?></td>
                <td style="vertical-align: middle;"><a href="<?=Url::to(['affiliate/completed', 'user_id' => $user->id]);?>"><?=$withdrawAmount;?></a></td>
                <td style="vertical-align: middle;"><?=$pendingCommission;?></td>
                <td style="vertical-align: middle;">
                  <a href="<?=Url::to(['affiliate/view', 'id' => $model->user_id]);?>" class="btn btn-sm green tooltips" data-container="body" data-original-title="Chi tiết affiliate"><i class="fa fa-eye"></i> Xem </a>
                  <a href="<?=Url::to(['affiliate/downgrade', 'id' => $model->user_id]);?>" class="btn btn-sm yellow link-action tooltips" data-container="body" data-original-title="Bỏ tư cách affiliate"><i class="fa fa-times"></i> Affiliate </a>
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