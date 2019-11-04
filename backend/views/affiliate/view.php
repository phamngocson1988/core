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

$commission = $user->getValidCommission();
$totalQuantity = 0;
$totalPending = 0;
$totalAvailable = 0;
$total = 0;
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thông tin chi tiết affiliate</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Thông tin chi tiết affiliate</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <span class="caption-subject bold uppercase">Thông tin chi tiết affiliate</span>
        </div>
        <div class="actions">
        </div>
      </div>
      <div class="portlet-body">
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> ID</th>
              <th> Tên </th>
              <th> Email </th>
              <th> Số điện thoại </th>
              <th> Preferred IM </th>
              <th> IM Account </th>
              <th> Channel Url </th>
              <th> Ngày duyệt </th>
            </tr>
          </thead>
          <tbody>
              <tr>
                <td style="vertical-align: middle;"><?=$affiliate->user_id;?></td>
                <td style="vertical-align: middle;"><?=$user->name;?></td>
                <td style="vertical-align: middle;"><?=$user->email;?></td>
                <td style="vertical-align: middle;"><?=$user->phone;?></td>
                <td style="vertical-align: middle;"><?=$affiliate->preferred_im;?></td>
                <td style="vertical-align: middle;"><?=$affiliate->im_account;?></td>
                <td style="vertical-align: middle;"><?=$affiliate->channel;?></td>
                <td style="vertical-align: middle;"><?=date('Y-m-d', strtotime($affiliate->updated_at));?></td>
              </tr>
          </tbody>
        </table>
        <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['affiliate/view', 'id' => $affiliate->user_id]]);?>
        <div class="row margin-bottom-10">
            <?php $member = $search->getMember();?>
            <?=$form->field($search, 'member_id', [
              'options' => ['class' => 'form-group col-md-4 col-lg-3'],
            ])->widget(kartik\select2\Select2::classname(), [
              'initValueText' => ($search->member_id) ? sprintf("%s - %s", $member->username, $member->email) : '',
              'options' => ['class' => 'form-control', 'name' => 'member_id'],
              'pluginOptions' => [
                'placeholder' => 'Tìm khách hàng',
                'allowClear' => true,
                'minimumInputLength' => 3,
                'ajax' => [
                    'url' => Url::to(['user/suggestion']),
                    'dataType' => 'json',
                    'processResults' => new JsExpression('function (data) {return {results: data.data.items};}')
                ]
              ]
            ])->label('Tìm khách hàng')?>

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
        <?php Pjax::begin(); ?>
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th> STT </th>
              <th> Mã KH </th>
              <th> Khách hàng </th>
              <th> Ngày đăng ký </th>
              <th> Doanh số (gói) </th>
              <th> Hoa hồng tích lũy </th>
              <th> Hoa hồng khả dụng </th>
              <th> Tổng hoa hồng </th>
              <th> Tác vụ </th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="9"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <?php $member = $model->member;?>
              <?php
              $memCommission = clone $commission;
              $memCommission->with('order');
              $memCommission->andWhere(['member_id' => $model->member_id]);
              $pending = clone $memCommission;
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

              $totalQuantity += $orderQuantities;
              $totalPending += $pendingCommission;
              $totalAvailable += $availableCommission;
              $total += $pendingCommission;
              ?>
              <tr>
                <td><?=$no + $pages->offset + 1;?></td>
                <td><?=$model->member_id;?></td>
                <td><?=$member->name;?></td>
                <td><?=date('Y-m-d', strtotime($member->created_at));?></td>
                <td><?=$orderQuantities;?></td>
                <td><?=$pendingCommission;?></td>
                <td><?=$availableCommission;?></td>
                <td><?=$pendingCommission;?></td>
                <td>
                  <a href='<?=Url::to(['affiliate/member', 'member_id' => $model->member_id]);?>' class="btn btn-xs grey-salsa tooltips" data-pjax="0" data-container="body" data-original-title="Chi tiết giao dịch"><i class="fa fa-eye"></i></a>
                </td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfooter>
            <td></td>
            <td></td>
            <td></td>
            <td>Tổng: <?=number_format($search->getCommand()->count());?></td>
            <td>Tổng: <?=number_format($totalQuantity, 1);?></td>
            <td>Tổng: <?=number_format($totalPending, 1);?></td>
            <td>Tổng: <?=number_format($totalAvailable, 1);?></td>
            <td>Tổng: <?=number_format($total, 1);?></td>
            <td></td>
          </tfooter>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>
