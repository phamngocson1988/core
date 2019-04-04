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
              <li>
                <a href="#images" data-toggle="tab"> Hình ảnh</a>
              </li>
              <li>
                <a href="#complain" data-toggle="tab"> Phản hồi</a>
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
                        <?php if (Yii::$app->user->can('handler')) :?>
                        <div class="actions">
                          <a href="javascript:;" id="edit_game_account" class="btn btn-default btn-sm"><i class="fa fa-pencil"></i> Edit </a>
                        </div>
                        <?php endif;?>
                      </div>
                      <div class="portlet-body" id="game_account">
                        <div class="row static-info">
                          <div class="col-md-5"> Username: </div>
                          <div class="col-md-7"> 
                            <?=$form->field($item, 'username', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Password: </div>
                          <div class="col-md-7">
                            <?=$form->field($item, 'password', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Tên nhân vật: </div>
                          <div class="col-md-7 value">
                            <?=$form->field($item, 'character_name', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Platform: </div>
                          <div class="col-md-7 value">
                            <?=$form->field($item, 'platform', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->dropDownList(['ios' => 'Ios', 'android' => 'Android'])->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Login method: </div>
                          <div class="col-md-7 value">
                            <?=$form->field($item, 'login_method', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->dropDownList(['google' => 'Google', 'facebook' => 'Facebook'])->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Recover Code: </div>
                          <div class="col-md-7 value"> 
                            <?=$form->field($item, 'recover_code', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Server: </div>
                          <div class="col-md-7 value">
                            <?=$form->field($item, 'server', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5 name"> Ghi chú: </div>
                          <div class="col-md-7 value">
                            <?=$form->field($item, 'note', [
                              'options' => ['class' => ''],
                              'inputOptions' => ['disabled' => true, 'class' => 'form-control']
                            ])->textInput()->label(false);?>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="images">
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <a class="btn red btn-outline sbold" id="before_image">Hình trước</a>
                    <input type="file" id="file_before_image" name="before_image" style="display: none" />
                    <?=$form->field($item, 'image_before_payment', [
                      'template' => '{input}', 
                      'options' => ['tag' => false],
                      'inputOptions' => ['id' => 'input_before_image']
                    ])->hiddenInput()->label(false);?>
                    <img src="<?=$item->getImageBeforeUrl();?>" id="show_before_image" class="img-responsive">
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <a class="btn red btn-outline sbold" id="after_image">Hình sau</a>
                    <input type="file" id="file_after_image" name="after_image" style="display: none" />
                    <?=$form->field($item, 'image_after_payment', [
                      'template' => '{input}', 
                      'options' => ['container' => false],
                      'inputOptions' => ['id' => 'input_after_image']
                    ])->hiddenInput()->label(false);?>
                    <img src="<?=$item->getImageAfterUrl();?>" id="show_after_image" class="img-responsive">
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="complain">
                <!-- Start -->
                <div class="row">
                  <div class="col-md-12">
                    <div class="portlet light portlet-fit bordered">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="icon-microphone font-green"></i>
                          <span class="caption-subject bold font-green uppercase"> Phản hồi từ khách hàng </span>
                        </div>
                        <div class="actions">
                          <a href="#complain_template" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi phản hồi</a>
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
                                  <a href="javascript:;" class="timeline-body-title font-blue-madison"><?=$complain->sender->name;?></a>
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
                <!-- End -->
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
<div class="modal fade" id="complain_template" tabindex="-1" role="basic" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
        <h4 class="modal-title">Chọn một câu trả lời để phản hồi đến khách hàng</h4>
      </div>
      <div class="modal-body" style="height: 200px; position: relative; overflow: auto; display: block;"> 
        <table class="table">
          <thead>
            <tr>
              <th scope="col" width="5%">#</th>
              <th scope="col" width="90%">Nội dung</th>
              <th scope="col" width="5%">Chọn</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($template_list as $template_item) :;?>
            <tr>
              <td><?=$template_item->id;?></td>
              <td><?=$template_item->content;?></td>
              <td>
                <?= Html::beginForm(['order/send-complain'], 'POST', ['class' => 'send-form']); ?>
                  <?= Html::hiddenInput('order_id', $order->id); ?>
                  <?= Html::hiddenInput('template_id', $template_item->id); ?>
                  <button type="submit" class="btn btn-default" data-toggle="modal"><i class="fa fa-plus"></i> Gửi</button>
                <?= Html::endForm(); ?>
              </td>
            </tr>
            <?php endforeach;?>
          </tbody>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn dark btn-outline" data-dismiss="modal">Close</button>
      </div>
    </div>
    <!-- /.modal-content -->
  </div>
  <!-- /.modal-dialog -->
</div>

<?php
$script = <<< JS
$('#edit_game_account').on('click', function(){
  $('#game_account').find('input, select').prop('disabled', false);
});
var beforeImage = new AjaxUploadFile({trigger_element: '#before_image', file_element: '#file_before_image'});
beforeImage.callback = function(result) {
  console.log(typeof result);
  console.log(result);
  $('#show_before_image').attr('src', result[0].src);
  $('#input_before_image').val(result[0].id)
}
var afterImage = new AjaxUploadFile({trigger_element: '#after_image', file_element: '#file_after_image'});
afterImage.callback = function(result) {
  console.log(result);
  $('#show_after_image').attr('src', result[0].src);
  $('#input_after_image').val(result[0].id)
};

var nextForm = new AjaxFormSubmit({element: '#next-form'});
nextForm.success = function (data, form) {
  location.reload();
}

var sendForm = new AjaxFormSubmit({element: '.send-form'});
sendForm.success = function (data, form) {
  location.reload();
}
JS;
$this->registerJs($script);
?>