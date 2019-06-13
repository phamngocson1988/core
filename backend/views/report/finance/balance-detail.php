<?php
use yii\widgets\LinkPager;
use yii\widgets\Pjax;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use yii\web\JsExpression;
use dosamigos\datetimepicker\DateTimePicker;
use common\models\Order;
use common\models\UserWallet;
use common\models\PaymentTransaction;
use common\models\Promotion;

$this->registerCssFile('vendor/assets/global/plugins/bootstrap-select/css/bootstrap-select.css', ['depends' => ['\yii\bootstrap\BootstrapAsset']]);
$this->registerJsFile('vendor/assets/global/plugins/bootstrap-select/js/bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
$this->registerJsFile('vendor/assets/pages/scripts/components-bootstrap-select.min.js', ['depends' => '\backend\assets\AppAsset']);
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
          <span class="caption-subject bold uppercase">Khách hàng <span style="color:red"><?=$user->name;?></span></span>
        </div>
        <div class="actions">
          <!-- <a role="button" class="btn btn-warning" href="<?=Url::current(['mode'=>'export']);?>"><i class="fa fa-file-excel-o"></i> Export</a> -->
        </div>
      </div>
      <div class="portlet-body">
        <div class="row margin-bottom-10">
          <?php $form = ActiveForm::begin(['method' => 'GET', 'action' => ['report/finance-balance-detail', 'id' => $search->user_id]]);?>
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
        <table class="table table-striped table-bordered table-hover table-checkable">
          <thead>
            <tr>
              <th style="width: 5%;"> <?=Yii::t('app', 'no');?> </th>
              <th style="width: 30%;"> Mã GD/Mã đơn hàng</th>
              <th style="width: 10%;"> Loại giao dịch </th>
              <th style="width: 25%;"> Thời gian hoàn thành </th>
              <th style="width: 10%;"> Kcoin </th>
              <th style="width: 10%;"> Số dư ban đầu</th>
              <th style="width: 10%;"> Số dư hiện tại</th>
            </tr>
          </thead>
          <tbody>
              <?php if (!$models) :?>
              <tr><td colspan="7"><?=Yii::t('app', 'no_data_found');?></td></tr>
              <?php endif;?>
              <?php foreach ($models as $no => $model) :?>
              <tr>
                <td>#<?=($pages->offset + $no + 1)?></td>
                <td style="vertical-align: middle;">
                  <?php switch ($model->ref_name) { 
                    case PaymentTransaction::className():
                      $object = PaymentTransaction::findOne(['auth_key' => $model->ref_key]);
                      if (!$object) break;
                      echo sprintf("Transaction <a href='%s'>#%s</a>", 'javascript:void(0)', $model->ref_key);
                      break;
                    case Order::className():
                      $object = Order::findOne(['auth_key' => $model->ref_key]);
                      if (!$object) break;
                      echo sprintf("Pay for order <a href='%s'>#%s</a>", Url::to(['order/view', 'id' => $object->id]), $object->auth_key);
                      break;
                    case Promotion::className():
                      $object = Promotion::findOne(['code' => $model->ref_key]);
                      if (!$object) break;
                      echo sprintf("Transaction <a href='%s'>#%s</a>", 'javascript:void(0)', $object->code);
                      break;
                  }?>
                </td>
                <td style="vertical-align: middle;">
                  <?php if ($model->type == UserWallet::TYPE_INPUT) : ?>
                    <span class="label label-success">Nạp tiền</span>
                  <?php else : ?> 
                    <span class="label label-default">Rút tiền</span>
                  <?php endif; ?>
                  <?php if ($model->ref_name == Promotion::className()): ?>
                    <span class="label label-warning">Khuyễn mãi</span>
                  <?php endif;?>
                </td>
                <td style="vertical-align: middle;"><?=$model->payment_at;?></td>
                <td style="vertical-align: middle;"><?=number_format($model->coin);?></td>
                <td style="vertical-align: middle;"><?=number_format($model->balance - $model->coin);?></td>
                <td style="vertical-align: middle;"><?=number_format($model->balance);?></td>
              </tr>
              <?php endforeach;?>
          </tbody>
          <tfoot>
            <tr>
              <td></td>
              <td></td>
              <td></td>
              <td></td>
              <td>Tổng cộng: <?=number_format($search->getCommand()->sum('coin'));?></td>
              <td></td>
              <td></td>
            </tr>
          </tfoot>
        </table>
        <?=LinkPager::widget(['pagination' => $pages])?>
        <?php Pjax::end(); ?>
      </div>
    </div>
    <!-- END EXAMPLE TABLE PORTLET-->
  </div>
</div>