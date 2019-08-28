<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use backend\models\Game;
use backend\models\Order;
use backend\models\OrderFile;
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
                <a href="#complain" data-toggle="tab"> Trợ giúp</a>
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
                          <?php if (Yii::$app->user->can('orderteam')) :?>
                          <?= Html::beginForm(['order/add-unit', 'id' => $order->id], 'post', ['id' => 'update-unit-form']) ?>
                          <div class="col-md-6">
                              <div class="input-group">
                                  <input type="number" id="doing_unit" class="form-control">
                                  <span class="input-group-btn">
                                      <button class="btn btn-default" id="update_unit" type="button">Xác nhận</button>
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
                          <?= Html::endForm();?>
                          <?php
                            $progress = <<< JS
                            var updateUnitForm = new AjaxFormSubmit({element: '#update-unit-form'});
                            updateUnitForm.success = function (data, form) {
                                var cur = $('#doing_unit_progress').attr('aria-valuemax');
                                var newpc = (data.total / cur) * 100;
                                $('#doing_unit_progress').css('width', newpc + '%');
                                $('#doing_unit_progress span').html(data.total + '(Complete)');
                                $('#current_doing_unit').html(data.total);
                                $('#doing_unit').val('');
                            }
                            JS;
                            ?>
                          <?php endif;?>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-6 col-sm-12">
                    <div class="portlet blue-hoki box">
                      <div class="portlet-title">
                        <div class="caption">
                          <i class="fa fa-cogs"></i>Order Details
                        </div>
                      </div>
                      <div class="portlet-body" id="game_account">
                        <div class="row static-info">
                          <div class="col-md-5">Username: </div>
                          <div class="col-md-7"><?=$order->username;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Password: </div>
                          <div class="col-md-7"><?=$order->password;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Tên nhân vật: </div>
                          <div class="col-md-7"><?=$order->character_name;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Platform: </div>
                          <div class="col-md-7"><?=$order->platform;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Login method: </div>
                          <div class="col-md-7"><?=$order->getLoginMethod();?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Recover Code: </div>
                          <div class="col-md-7"><?=$order->recover_code;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Server: </div>
                          <div class="col-md-7"><?=$order->server;?></div>
                        </div>
                        <div class="row static-info">
                          <div class="col-md-5">Ghi chú: </div>
                          <div class="col-md-7"><?=$order->note;?></div>
                        </div>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="<?=Url::to(['order/index']);?>" class="btn default"><i class="fa fa-angle-left"></i> <?=Yii::t('app', 'back')?></a>
                                    <a class="btn red btn-outline sbold" data-toggle="modal" href="#next"><i class="fa fa-angle-right"></i> Chuyến tới trạng thái Processing</a>
                                </div>
                            </div>
                        </div>
                        <?php if ($order->hasCancelRequest()) :?>
                        <div class="form-actions">
                            <div class="row">
                                <div class="col-md-offset-3 col-md-9">
                                    <a href="<?=Url::to(['order/approve', 'id' => $order->id]);?>" class="btn green" id="cancel_order"><i class="fa fa-check"></i> Đồng ý hủy đơn</a>
                                    <a class="btn red btn-outline sbold" data-toggle="modal" href="#disapprove"><i class="fa fa-ban"></i> Không chấp nhận</a>
                                </div>
                            </div>
                        </div>
                        <?php endif;?>
                      </div>
                    </div>
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
                    <a class="btn red btn-outline sbold" id="uploadElement">Tải hình ảnh trước khi hoàn thành</a>
                    <input type="file" id="uploadEvidence" name="uploadEvidence[]" style="display: none" multiple/>
                  </div>
                </div>
                <div class="row" id="evidences">
                  <?php echo $this->render('@backend/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_BEFORE), 'can_edit' => Yii::$app->user->can('edit_order', ['order' => $order])]);?>
                </div>
                <hr/>
                <div class="row" style="margin-bottom: 20px">
                  <div class=col-md-12>
                    <a class="btn red btn-outline sbold" id="uploadElementAfter">Tải hình ảnh sau khi hoàn thành</a>
                    <input type="file" id="uploadEvidenceAfter" name="uploadEvidenceAfter[]" style="display: none" multiple/>
                  </div>
                </div>
                <div class="row" id="evidences_after">
                  <?php echo $this->render('@backend/views/order/_evidence.php', ['images' => $order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_AFTER), 'can_edit' => Yii::$app->user->can('edit_order', ['order' => $order])]);?>
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