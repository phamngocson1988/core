<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Game;
use common\models\Product;
?>
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
      <span>Chỉnh sửa đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Chỉnh sửa đơn hàng</h1>
<!-- END PAGE TITLE-->
<div class="row">
  <div class="col-md-12">
    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated']]);?>
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"> Order #<?=$order->id;?>
              <span class="hidden-xs">| <?=$order->created_at;?> </span>
            </span>
          </div>
          <div class="actions btn-set">
            <a href="<?=$back;?>" class="btn default">
            <i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
            <?php if (Yii::$app->user->can('handler')) :?>
            <button type="submit" class="btn btn-success">
            <i class="fa fa-check"></i> <?=Yii::t('app', 'save')?>
            </button>
            <a class="btn red btn-outline sbold" data-toggle="modal" href="#next">
            <i class="fa fa-angle-right"></i> <?=Yii::t('app', 'processing')?></a>
            <?php endif?>
          </div>
        </div>
        <div class="portlet-body">
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
                <div class="row">
                  <div class="col-md-4 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Order Details 
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5 name"> Order #: </div>
                          <div class="col-md-7 value"> <?=$order->id;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Order Date & Time: </div>
                          <div class="col-md-7 value"> <?=$order->created_at;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Order Status: </div>
                          <div class="col-md-7 value">
                            <span class="label label-success"> <?=$order->status;?> </span>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Total: </div>
                          <div class="col-md-7 value"> (K) <?=number_format($order->total_price);?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Payment Information: </div>
                          <div class="col-md-7 value"> King Coin </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Customer Information 
                        </div>
                      </div>
                      <?php $customer = $order->customer;?>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5 name"> Customer Name: </div>
                          <div class="col-md-7 value"> <?=$customer->name;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Email: </div>
                          <div class="col-md-7 value"> <?=$customer->email;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Phone Number: </div>
                          <div class="col-md-7 value"> <?=sprintf("(%s)%s", $customer->country_code, $customer->phone);?> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="col-md-4 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Thông tin nạp game
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5 name"> Username: </div>
                          <div class="col-md-7 value"> <?=$item->username;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Password: </div>
                          <div class="col-md-7 value"> <?=$item->password;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Tên nhân vật: </div>
                          <div class="col-md-7 value"> <?=$item->character_name;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Platform: </div>
                          <div class="col-md-7 value"> <?=$item->platform;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Recover Code: </div>
                          <div class="col-md-7 value"> <?=$item->recover_code;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Server: </div>
                          <div class="col-md-7 value"> <?=$item->server;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Ghi chú: </div>
                          <div class="col-md-7 value"> <?=$item->note;?> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-12 col-sm-12">
                    <div class="portlet grey-cascade box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Game
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="table-responsive">
                          <table class="table table-hover table-bordered table-striped">
                            <thead>
                              <tr>
                                <th> Tên game </th>
                                <th> Số đơn vị </th>
                                <th> Tên đơn vị </th>
                                <th> Số lượng </th>
                                <th> Tổng số </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><?=$item->item_title;?></td>
                                <td><?=$item->unit_name;?></td>
                                <td><?=$item->unit;?></td>
                                <td><?=$item->quantity;?></td>
                                <td><?=$item->total_unit;?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- <div class="row">
                  <div class="form-body">

                  </div>
                </div> -->
              </div>
            </div>
          </div>
        </div>
      </div>
      <?php ActiveForm::end()?>
  </div>
</div>
<div class="modal fade" id="next" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Move the order to processing</h4>
      </div>
      <?php $nextForm = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated', 'id' => 'next-form'], 'action' => Url::to(['order/move-to-processing'])]);?>
      <?=$nextForm->field($updateStatusForm, 'id', [
        'template' => '{input}', 
        'options' => ['container' => false]
      ])->hiddenInput(['value' => $order->id])->label(false);?>
      <div class="modal-body"> 
          <p>Bạn có chắc chắn muốn chuyển đơn hàng này sang trạng thái "Processing"</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
        <button type="submit" class="btn green">Save changes</button>
      </div>
      <?php ActiveForm::end();?>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>
