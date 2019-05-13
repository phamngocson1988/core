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
      <div class="portlet">
        <div class="portlet-title">
          <div class="caption">
            <i class="icon-settings font-dark"></i>
            <span class="caption-subject font-dark sbold uppercase"> Order #<?=$order->id;?>
              <span class="hidden-xs">| <?=$order->created_at;?> </span>
            </span>
          </div>
        </div>
        <div class="portlet-body">
          <div class="form-wizard">
            <div class="form-body">
              <ul class="nav nav-pills nav-justified steps">
                <li class="active">
                  <a href="javasciprt:;" class="step">
                  <span class="number"> 1 </span>
                  <span class="desc">
                  <i class="fa fa-check"></i> Verifying</span>
                  <p style="color: #CCC">Đơn hàng chưa thanh toán</p> 
                  </a>
                </li>
                <li class="active">
                  <a href="javasciprt:;" class="step">
                  <span class="number"> 2 </span>
                  <span class="desc">
                  <i class="fa fa-check"></i> Pending </span>
                  <p style="color: #CCC">Đơn hàng đã thanh toán</p> 
                  </a>
                </li>
                <li>
                  <a href="javasciprt:;" class="step">
                  <span class="number"> 3 </span>
                  <span class="desc">
                  <i class="fa fa-check"></i> Processing </span>
                  <p style="color: #CCC">Đơn hàng đã thực hiện xong</p> 
                  </a>
                </li>
                <li>
                  <a href="javasciprt:;" class="step">
                  <span class="number"> 4 </span>
                  <span class="desc">
                  <i class="fa fa-check"></i> Completed </span>
                  <p style="color: #CCC">Đơn hàng đã hoàn tất</p> 
                  </a>
                </li>
              </ul>
              <div id="bar" class="progress progress-striped" role="progressbar">
                <div class="progress-bar progress-bar-success" style="width: 50%"> </div>
              </div>
            </div>
          </div>
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs">
              <li class="active">
                <a href="#tab_general" data-toggle="tab"> <?=Yii::t('app', 'main_content')?></a>
              </li>
            </ul>
            <div class="tab-content">
              <div class="tab-pane active" id="tab_general">
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
                                <th> Số lượng nạp </th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><?=$order->game_title;?></td>
                                <td><?=$order->total_unit;?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        
                      </div>
                    </div>
                  </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-6">
                        <div class="input-group">
                            <input type="number" id="doing_unit" class="form-control">
                            <span class="input-group-btn">
                                <button class="btn btn-default" id="update_unit" type="button">Nạp game</button>
                            </span>
                        </div><!-- /input-group -->
                    </div>
                    <div class="col-md-6">
                        <div class="progress progress-striped active">
                            <div id="doing_unit_progress" class="progress-bar progress-bar-warning" role="progressbar" aria-valuenow="<?=$order->doing_unit;?>" aria-valuemin="0" aria-valuemax="<?=$order->total_unit;?>" style="width: <?=$order->getPercent();?>%">
                                <span id='current_doing_unit'><?=$order->doing_unit;?></span> / <?=$order->total_unit;?>
                            </div>
                        </div>
                    </div>
                    
                </div>
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <?php $form = ActiveForm::begin(['options' => ['class' => 'form-horizontal form-row-seperated form']]);?>
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Thông tin nạp game
                        </div>
                      </div>
                      <div class="portlet-body" id="game_account">
                        <div class="row static-info">
                          <div class="col-md-5"> Username: </div>
                          <div class="col-md-7"> 
                            <?=$form->field($order, 'username', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Password: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'password', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Tên nhân vật: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'character_name', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Platform: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'platform', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->dropDownList(['ios' => 'Ios', 'android' => 'Android'])->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Login method: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'login_method', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->dropDownList(['google' => 'Google', 'facebook' => 'Facebook'])->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Recover Code: </div>
                          <div class="col-md-7"> 
                            <?=$form->field($order, 'recover_code', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Server: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'server', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Ghi chú: </div>
                          <div class="col-md-7">
                            <?=$form->field($order, 'note', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="<?=Url::to(['order/index']);?>" class="btn default"><i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
                                    <button type="submit" class="btn green">Submit</button>
                                    <a class="btn red btn-outline sbold" data-toggle="modal" href="#next"><i class="fa fa-angle-right"></i> Confirm</a>
                                </div>
                            </div>
                        </div>
                      </div>
                    </div>
                    <?php ActiveForm::end()?>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Order Details 
                        </div>
                      </div>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5"> Order #: </div>
                          <div class="col-md-7"> <?=$order->id;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Order Date & Time: </div>
                          <div class="col-md-7"> <?=$order->created_at;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Order Status: </div>
                          <div class="col-md-7">
                            <span class="label label-success"> <?=$order->status;?> </span>
                          </div>
                        </div>
                        <?php if ($order->total_discount) :?>
                        <div class="row static-info">
                          <div class="col-md-5"> Sub total: </div>
                          <div class="col-md-7"> (K) <?=number_format($order->sub_total_price);?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Discount: </div>
                          <div class="col-md-7"> (K) <?=number_format($order->total_discount);?> </div>
                        </div>
                        <?php endif;?>
                        <div class="row static-info">
                          <div class="col-md-5"> Total: </div>
                          <div class="col-md-7"> (K) <?=number_format($order->total_price);?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Payment Information: </div>
                          <div class="col-md-7"> King Coin </div>
                        </div>
                      </div>
                    </div>
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Customer Information 
                        </div>
                      </div>
                      <?php $customer = $order->customer;?>
                      <div class="portlet-body">
                        <div class="row static-info">
                          <div class="col-md-5"> Customer: </div>
                          <div class="col-md-7"> <?=$customer->name;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Email: </div>
                          <div class="col-md-7"> <?=$customer->email;?> </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Phone Number: </div>
                          <div class="col-md-7"> <?=sprintf("(%s)%s", $customer->country_code, $customer->phone);?> </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
  </div>
</div>

<?php
$script = <<< JS
$('#edit_game_account').on('click', function(){
  $('#game_account').find('input, select').prop('disabled', false);
});

var nextForm = new AjaxFormSubmit({element: '#next-form'});
nextForm.success = function (data, form) {
  location.reload();
}

var sendForm = new AjaxFormSubmit({element: '.send-form'});
sendForm.success = function (data, form) {
  location.reload();
}

// Update doing unit
$('#update_unit').on('click', function(e) {
  console.log($('#doing_unit').val());
  e.preventDefault();
  e.stopImmediatePropagation();
  $.ajax({
    url: '###UPDATE_UNIT###',
    type: 'POST',
    dataType : 'json',
    data: {doing_unit: $('#doing_unit').val()},
    success: function (result, textStatus, jqXHR) {
      if (result.status == false) {
          alert(result.errors);
          return false;
      } else {
        var cur = $('#doing_unit_progress').attr('aria-valuemax');
        var newpc = (result.data.total / cur) * 100;
        $('#doing_unit_progress').css('width', newpc + '%');
        $('#doing_unit_progress span').html(result.data.total + '(Complete)');
        $('#current_doing_unit').html(result.data.total);
        $('#doing_unit').val('');
      }
    },
  });
  return false;
});



JS;
$redirect = Url::to(['order/add-unit', 'id' => $order->id]);
$script = str_replace('###UPDATE_UNIT###', $redirect, $script);
$this->registerJs($script);
?>