<?php
use yii\helpers\Html;
use yii\helpers\Url;
use yii\helpers\ArrayHelper;
use yii\widgets\ActiveForm;
use dosamigos\datepicker\DatePicker;
use dosamigos\datepicker\DateRangePicker;
use yii\web\JsExpression;
use common\models\Game;
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
      <span>Xem đơn hàng</span>
    </li>
  </ul>
</div>
<!-- END PAGE BAR -->
<!-- BEGIN PAGE TITLE-->
<h1 class="page-title">Xem đơn hàng</h1>
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
                <div class="row" id="evidences">
                <?php foreach ($order->evidences as $image) : ?>
                <div class="col-md-2 col-sm-3 mt-element-overlay image-item">
                    <div class="mt-overlay-1">
                        <img src="<?=$image->getUrl();?>" height="200" width="200">
                        <div class="mt-overlay">
                        <ul class="mt-info">
                            <li>
                            <a class="btn default btn-outline" href="<?=$image->getUrl();?>" target="_blank"><i class="icon-link"></i></a>
                            </li>
                        </ul>
                        </div>
                    </div>
                </div>
                <?php endforeach;?>
                </div>
                <hr/>
                <div class="row" id="evidences_after">
                  <?php foreach ($order->getEvidencesByType(OrderFile::TYPE_EVIDENCE_AFTER) as $image) : ?>
                  <div class="col-md-2 col-sm-3 mt-element-overlay image-item">
                      <div class="mt-overlay-1">
                          <img src="<?=$image->getUrl();?>" height="200" width="200">
                          <div class="mt-overlay">
                          <ul class="mt-info">
                              <li>
                              <a class="btn default btn-outline" href="<?=$image->getUrl();?>" target="_blank"><i class="icon-link"></i></a>
                              </li>
                          </ul>
                          </div>
                      </div>
                  </div>
                  <?php endforeach;?>
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
<?php
$script = <<< JS
JS;
$this->registerJs($script);
?>