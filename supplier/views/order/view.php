<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use supplier\models\Game;
use supplier\models\Order;
use supplier\models\OrderFile;
use supplier\models\OrderSupplier;

?>
<style type="text/css">
  .button-percent.active {
    background-color: #32c5d2;
  }
  .button-percent {
    border-color: #ccc;
    background-color: white;
    color: black;
    border: solid 2px #CCC;
  }
  .flex-container {
    padding: 10px;
    margin-bottom: 15px;
    height: 55px;
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
      <a href="<?=Url::to(['order/index'])?>">Quản lý đơn hàng</a>
      <i class="fa fa-circle"></i>
    </li>
    <li>
      <span>Xem thông tin đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <div class="portlet light bordered">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-settings"></i>
          <span class="caption-subject sbold">Order #<?=$order->id;?> <span class="hidden-xs">| <?=$order->created_at;?> </span> </span>
        </div>
      </div>
      <div class="portlet-body">
        <?php echo $this->render('@supplier/views/order/_step.php', ['order' => $model]);?>

        <?php if (($model->isApprove() && $countComplain) || $model->isProcessing() || $model->isCompleted() ||  $model->isConfirmed()) :?>
        <div class="table-responsive">
          <table class="table table-hover table-bordered table-striped">
            <thead>
              <tr>
                <th> Tên game </th>
                <th> Số lượng cần nạp </th>
                <th> Số lượng đã nạp </th>
                <th> Số tiền cho game </th>
                <th> Số tiền nhận được </th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><?=$model->getGameTitle();?></td>
                <td><?=$model->quantity;?></td>
                <td><?=$model->doing;?></td>
                <td><?=number_format($model->price, 1);?></td>
                <td><?=number_format($model->total_price, 1);?></td>
              </tr>
            </tbody>
          </table>
        </div>
        <?php endif;?>
        <div class="row">
          <div class="col-md-3">
            <div class="flex-container" style="text-align: center; background-color: #67809F;">
              <?php if ($model->isApprove()) {
                $loginStatus = 'Pending';
                if ($countComplain) $loginStatus = 'Pending Information';
              } else {
                $loginStatus = 'Login Successfully';
              }
              ?>
              <p style="padding-top: 8px; padding-bottom: 5px; flex-grow: 1; color: white;"> + Login status: </p> 
              <div style="color: #F1C40F; padding-top: 7px; padding-bottom: 5px; flex-grow: 2; background-color: white; font-weight: bold;"><?=$loginStatus;?></div>
            </div>
          </div>
          <?php if ($model->isProcessing() || $model->isCompleted() ||  $model->isConfirmed()) :?>
          <div class="col-md-9">
            <div class="flex-container" style="text-align: center; background-color: #67809F;">
              <p style="padding-top: 8px; flex-grow: 1; color: white"> + Cập nhật tiến độ: </p> 
              <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md button-percent" disabled="true" data-value="20" href="javascript:;"> 20% </a>
              <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md button-percent" disabled="true" data-value="50" href="javascript:;"> 50% </a>
              <a role="button" style="height: 35px; margin-right: 10px; flex-grow: 1;" class="btn btn-md button-percent" disabled="true" data-value="70" href="javascript:;"> 70% </a>
              <div class="inline" style="margin: 0; flex-grow: 8; padding-top: 7px">
                <div class="progress progress-striped active" style="margin: 0;">
                  <div id="doing_unit_progress" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=$model->quantity;?>" aria-valuemin="0" aria-valuemax="<?=$model->quantity;?>" style="width: <?=$model->percent;?>%">
                      <span id='current_doing_unit' style="color: black"><?=$model->percent;?> %</span>
                  </div>
                </div>
              </div>
            </div>
          </div>
          <?php endif;?>
        </div>
        <div class="row">
          <div class="col-md-3 col-sm-6">
            <div class="portlet blue-hoki box" style="margin-bottom: 0">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-cogs"></i>Order Details
                </div>
              </div>
            </div>
            <table class="table table-hover table-striped table-bordered">
              <tr>
                <td> Order ID </td>
                <td><?=$order->id;?></td>
              </tr>
              <?php if ($order->bulk) : ?>
              <tr>
                <td> Order detail</td>
                <td><?=nl2br($order->raw);?></td>
              </tr>
              <?php else : ?>
              <tr>
                <td> Username </td>
                <td><?=$order->username;?></td>
              </tr>
              <tr>
                <td> Password </td>
                <td><?=$order->password;?></td>
              </tr>
              <tr>
                <td> Tên nhân vật </td>
                <td><?=$order->character_name;?></td>
              </tr>
              <tr>
                <td> Platform </td>
                <td><?=$order->platform;?></td>
              </tr>
              <tr>
                <td> Login method </td>
                <td><?=$order->getLoginMethod();?></td>
              </tr>
              <tr>
                <td> Recover Code </td>
                <td><?=$order->recover_code;?></td>
              </tr>
              <tr>
                <td> Server </td>
                <td><?=$order->server;?></td>
              </tr>
              <tr>
                <td> Ghi chú </td>
                <td><?=$order->note;?></td>
              </tr>
              <?php endif;?>
            </table>
          </div>
          <div class="col-md-3 col-sm-6">
            <div class="row" id="evidences">
                <?php echo $this->render('@supplier/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE)]);?>
            </div>
            <div>
              <span class="label label-success"><i class="fa fa-lightbulb-o"></i></span>
              <span> Vui lòng tải đủ ảnh cần thiết và đúng ảnh account để tránh khiếu kiện từ khách hàng </span>
              <div class="form-group">
                <label class="form-label">Password: [Nếu có]</label>
                <input type="number" class="form-control" maxlength="8" disabled="true" placeholder="Tối đa 8 số" id="supplier_password">
              </div>
            </div>
          </div>
          <div class="col-md-6 col-sm-12">
            <div class="portlet light portlet-fit bordered">
              <div class="portlet-title">
                <div class="caption">
                  <i class="icon-microphone font-green"></i>
                  <span class="caption-subject bold font-green uppercase"> Phản hồi từ khách hàng </span>
                </div>
              </div>
              <div class="portlet-body">
                <div class="timeline">
                  <?php foreach ($order->complains as $complain):?>
                  <div class="timeline-item">
                    <div class="timeline-badge">
                      <?php if ($complain->sender->avatarImage) :?>
                      <img class="timeline-badge-userpic" src="<?=$complain->sender->getAvatarUrl();?>"> 
                      <?php else : ?>
                        <div class="timeline-icon">
                          <i class="icon-user-following font-green-haze"></i>
                        </div>
                      <?php endif; ?>
                    </div>
                    <div class="timeline-body">
                      <div class="timeline-body-arrow"> </div>
                      <div class="timeline-body-head">
                        <div class="timeline-body-head-caption">
                          <a href="javascript:void()" class="timeline-body-title font-blue-madison"><?=$complain->isCustomer() ? 'Khách hàng' : $complain->sender->name;?></a>
                          <span class="timeline-body-time font-grey-cascade">Phản hồi vào lúc <?=$complain->created_at;?></span>
                        </div>
                      </div>
                      <div class="timeline-body-content">
                        <span class="font-grey-cascade"><?=$complain->content;?></span>
                      </div>
                    </div>
                  </div>
                  <?php endforeach;?>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
