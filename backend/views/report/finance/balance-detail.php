<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use backend\components\datetimepicker\DateTimePicker;
use common\models\Order;
use common\models\UserWallet;
use common\models\PaymentTransaction;
use common\models\Promotion;
use common\components\helpers\StringHelper;

$user = $search->getUser();
?>

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
  <ul class="page-breadcrumb">
    <li>
      <a href="/"><?=Yii::t('app', 'home')?></a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê & báo cáo</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Thống kê dòng tiền</span>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Lịch sử giao dịch</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Lịch sử giao dịch</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <!-- BEGIN EXAMPLE TABLE PORTLET-->
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption font-dark">
          <i class="icon-settings font-dark"></i>
          <?php if ($user) : ?>
          <span class="caption-subject bold uppercase">Khách hàng <span style="color:red"><?=$user->name;?></span></span>
          <?php else : ?>
          <span class="caption-subject bold uppercase">Khách hàng</span>
          <?php endif;?>
        </div>
        <div class="actions">
          <!-- <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a> -->
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/finance-balance-detail']]);?>
            <?=$form->field($search, 'user_id', [
              'options' => ['tag' => false],
              'inputOptions' => ['name' => 'user_id'],
              'template' => '{input}'
            ])->hiddenInput();?>

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
          <?php ActiveForm::end()?>
        </div>
        
        <?php Pjax::begin(); ?>
        <div class="table-responsive">
          <table class="table table-striped table-bordered table-hover table-checkable">
            <thead>
              <tr>
                <th col-tag="id"> <?=Yii::t('app', 'no');?> </th>
                <th col-tag="description"> Mô tả </th>
                <th col-tag="type"> Loại giao dịch </th>
                <th col-tag="payment_at"> Thời gian hoàn thành</th>
                <th col-tag="coin"> Kcoin</th>
                <th col-tag="balance_start"> Số dư ban đầu </th>
                <th col-tag="balance_end"> Số dư hiện tại </th>
              </tr>
            </thead>
            <tbody>
                <?php if (!$models) :?>
                <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
                <?php endif;?>
                <?php foreach ($models as $no => $model) :?>
                <tr>
                  <td col-tag="id">#<?=($pages->offset + $no + 1)?></td>
                  <td col-tag="description">
                    <?=$model['description'];?>
                  </td>
                  <td col-tag="type" class="center">
                    <?php if ($model['type'] == UserWallet::TYPE_INPUT) : ?>
                      <span class="label label-success">Nạp tiền</span>
                    <?php else : ?> 
                      <span class="label label-default">Rút tiền</span>
                    <?php endif; ?>
                    <?php if ($model['ref_name'] == Promotion::className()): ?>
                      <span class="label label-warning">Khuyễn mãi</span>
                    <?php endif;?>
                  </td>
                  <td col-tag="payment_at" class="center"><?=$model['payment_at'];?></td>
                  <td col-tag="coin" class="center"><?=StringHelper::numberFormat($model['coin'], 2);?></td>
                  <td col-tag="balance_start" class="center"><?=StringHelper::numberFormat($model['balance_start'], 2);?></td>
                  <td col-tag="balance_end" class="center"><?=StringHelper::numberFormat($model['balance_end'], 2);?></td>
                </tr>
                <?php endforeach;?>
            </tbody>
            <tfoot>
              <tr>
                <th col-tag="id"></th>
                <th col-tag="description"></th>
                <th col-tag="type"></th>
                <th col-tag="payment_at"></th>
                <th col-tag="coin" class="center">Tổng cộng: <?=number_format($search->getCommand()->sum('coin'), 1);?></th>
                <th col-tag="balance_start"></th>
                <th col-tag="balance_end"></th>
              </tr>
            </tfoot>
          </table>
        </div>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>