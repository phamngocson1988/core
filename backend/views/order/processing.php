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
          <?php echo $this->render('@backend/views/order/_step.php', ['order' => $order]);?>
          <div class="tabbable-bordered">
            <ul class="nav nav-tabs" role="tablist">
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
                        <?php echo $this->render('@backend/views/order/_unit.php', ['order' => $order]);?>
                        <div class="row static-info">
                          <div class="col-md-12">
                            <strong>Số game đã nạp: <?=$order->doing_unit;?></strong>
                          </div>
                        </div>
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
                          <div class="col-md-7"> <?=$order->username;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Password: </div>
                          <div class="col-md-7"><?=$order->password;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Tên nhân vật: </div>
                          <div class="col-md-7"> <?=$order->character_name;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Platform: </div>
                          <div class="col-md-7"> <?=$order->platform;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Login method: </div>
                          <div class="col-md-7"> <?=$order->login_method;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Recover Code: </div>
                          <div class="col-md-7"> <?=$order->recover_code;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Server: </div>
                          <div class="col-md-7"> <?=$order->server;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5"> Ghi chú: </div>
                          <div class="col-md-7"> <?=$order->note;?></div>
                        </div>
                      </div>
                    </div>
                    <?php ActiveForm::end()?>
                  </div>
                  <div class="col-md-6 col-sm-12">
                    <?php echo $this->render('@backend/views/order/_detail.php', ['order' => $order]);?>
                    <?php echo $this->render('@backend/views/order/_customer.php', ['order' => $order]);?>
                  </div>
                </div>
              </div>
              <div class="tab-pane" id="images">
                <div class="row" style="margin-bottom: 20px">
                  <div class=col-md-12>
                    <a class="btn red btn-outline sbold" id="uploadElement">Tải hình ảnh</a>
                    <input type="file" id="uploadEvidence" name="uploadEvidence[]" style="display: none" multiple/>
                  </div>
                </div>
                <div class="row" id="evidences">
                  <?php echo $this->render('@backend/views/order/_evidence.php', ['images' => $order->evidences, 'can_edit' => Yii::$app->user->can('edit_order', ['order' => $order])]);?>
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
  </div>
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
var sendForm = new AjaxFormSubmit({element: '.send-form'});
sendForm.success = function (data, form) {
  location.reload();
}

// delete
$('body').on('click', '.delete', function(e) {
    e.preventDefault();
    e.stopImmediatePropagation();
    if (!window.confirm('Bạn có muốn xóa hình này không?')) {
      return;
    }
    var element = $(this);
    $.ajax({
      url: $(this).attr('href'),
      type: 'DELETE',
      dataType : 'json',
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
          console.log(result.errors);
          return false;
        } else {
          element.closest('.image-item').remove();
        }
      }
    });
    return false;
});

var upload = new AjaxUploadFile({trigger_element: '#uploadElement', file_element: '#uploadEvidence'});
upload.callback = function(result) {
  result.forEach(function(element) {
    $.ajax({
      url: '###ADD_EVIDENCE_URL###',
      type: 'POST',
      dataType : 'json',
      data: {'OrderFile[file_id]': element.id},
      success: function (result, textStatus, jqXHR) {
        if (result.status == false) {
            alert('Error occur with #' + element.id);
            return false;
        } else {
          $('#evidences').html(result.data.html);
        }
      },
    });
  });
}
JS;
$addEvidenceUrl = Url::to(['order/add-evidence-image', 'id' => $order->id]);
$script = str_replace('###ADD_EVIDENCE_URL###', $addEvidenceUrl, $script);
$this->registerJs($script);
?>